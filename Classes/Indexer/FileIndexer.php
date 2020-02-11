<?php
declare(strict_types=1);

namespace Iresults\Pictures\Indexer;

use Exception;
use InvalidArgumentException;
use Iresults\Pictures\Domain\Model\Picture;
use Iresults\Pictures\Domain\Repository\PictureRepository;
use Iresults\Pictures\Domain\ValueObject\VariantConfiguration;
use Iresults\Pictures\Exception\StorageDriverTypeException;
use Iresults\Pictures\Service\ExifService;
use Iresults\Pictures\Service\ImageVariantService;
use Prewk\Result;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use function sprintf;

class FileIndexer implements IndexerInterface
{
    const FOLDER_NAME = 'ir_pictures';

    /**
     * @var ResourceFactory
     */
    private $resourceFactory;

    /**
     * @var PictureRepository
     */
    private $pictureRepository;

    /**
     * @var ImageVariantService
     */
    private $imageVariantService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var PersistenceManagerInterface
     */
    private $persistenceManager;

    /**
     * File Indexer constructor
     *
     * @param LoggerInterface             $logger
     * @param ResourceFactory             $resourceFactory
     * @param PictureRepository           $pictureRepository
     * @param ImageVariantService         $imageVariantService
     * @param PersistenceManagerInterface $persistenceManager
     */
    public function __construct(
        LoggerInterface $logger,
        ResourceFactory $resourceFactory,
        PictureRepository $pictureRepository,
        ImageVariantService $imageVariantService,
        PersistenceManagerInterface $persistenceManager
    ) {
        $this->resourceFactory = $resourceFactory;
        $this->pictureRepository = $pictureRepository;
        $this->imageVariantService = $imageVariantService;
        $this->logger = $logger;
        $this->persistenceManager = $persistenceManager;
    }

    public function index(IndexerParameterInterface $parameter): Result
    {
        $file = $parameter->getInner();
        if (!($file instanceof File)) {
            throw new InvalidArgumentException('Argument for index must be an instance of File');
        }
        $storageException = StorageDriverTypeException::assertSupportedDriverType($file->getStorage());
        if (null !== $storageException) {
            return new Result\Err($storageException);
        }
        if ($this->fileNeedsIndexing($file)) {
            $this->logger->debug(sprintf('File %s needs to be (re)indexed', $file->getPublicUrl()));

            $this->buildVariants($file);

            return $this->buildOrUpdatePicture($file);
        } else {
            $this->logger->debug('No reindexing necessary');

            return new Result\Ok('No reindexing necessary');
        }
    }

    /**
     * Determine if the given File needs to be indexed
     *
     * @param File $file
     * @return bool
     */
    public function fileNeedsIndexing(File $file): bool
    {
        $picture = $this->pictureRepository->findByFile($file);
        if ($picture === null) {
            return true;
        }

        if ($picture->getFileHash() !== $file->getSha1()) {
            return true;
        }

        foreach ($this->getVariantConfigurations() as $configuration) {
            $variantFileExists = $configuration->getFolder()->hasFile($file->getName());
            if (!$variantFileExists) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return VariantConfiguration[]
     */
    private function getVariantConfigurations(): array
    {
        $folder = $this->getVariantParentFolder();

        return [
            VariantConfiguration::build($folder, '100m'),
        ];
    }

    /**
     * @param File $file
     */
    private function buildVariants(File $file)
    {
        $this->imageVariantService->buildVariantsForImage($file, $this->getVariantConfigurations());
    }

    private function buildOrUpdatePicture(File $file): Result
    {
        if ($this->pictureRepository->containsEntryForFile($file)) {
            $result = $this->updatePicture($file);
            if ($result->isErr()) {
                return $result;
            }
        } else {
            $result = $this->createPicture($file);
            if ($result->isErr()) {
                return $result;
            }
        }

        /** @var Picture $indexEntry */
        $indexEntry = $result->ok()->unwrap();

        try {
            $this->persistenceManager->persistAll();

            return new Result\Ok(
                sprintf(
                    'Stored index for file #%d %s',
                    $indexEntry->getUid(),
                    $indexEntry->getFile()->getPublicUrl()
                )
            );
        } catch (Exception $exception) {
            return new Result\Err($exception);
        }
    }

    /**
     * @param File $file
     * @return Result
     */
    private function updatePicture(File $file): Result
    {
        try {
            $picture = $this->pictureRepository->findByFile($file);
            $this->enrichPicture($picture, $file);
            $this->pictureRepository->update($picture);

            return new Result\Ok($picture);
        } catch (Exception $exception) {
            return new Result\Err($exception);
        }
    }

    private function createPicture(File $file)
    {
        $picture = new Picture($file);

        try {
            $this->enrichPicture($picture, $file);
            $this->pictureRepository->add($picture);

            return new Result\Ok($picture);
        } catch (Exception $exception) {
            return new Result\Err($exception);
        }
    }

    private function enrichPicture(Picture $picture, File $file)
    {
        // TODO: Extract meta data
        $picture->setByline('byline');
        $picture->setHeadline('headline');
        $picture->setCaption('caption');
        $picture->setCopyrightString('copyright');
        $picture->setFile($file);
    }

    /**
     * @return Folder
     */
    private function getVariantParentFolder()
    {
        $storage = $this->resourceFactory->getDefaultStorage();
        if ($storage->hasFolder(self::FOLDER_NAME)) {
            return $storage->getFolder(self::FOLDER_NAME);
        } else {
            return $storage->createFolder(self::FOLDER_NAME);
        }
    }
}
