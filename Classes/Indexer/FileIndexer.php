<?php
declare(strict_types=1);

namespace Iresults\Pictures\Indexer;

use Exception;
use InvalidArgumentException;
use Iresults\Pictures\Domain\Model\Picture;
use Iresults\Pictures\Domain\Repository\PictureRepository;
use Iresults\Pictures\Domain\ValueObject\VariantConfiguration;
use Iresults\Pictures\Exception\StorageDriverTypeException;
use Iresults\Pictures\Service\ImageVariantService;
use Iresults\Pictures\Service\MetadataService;
use Prewk\Result;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use function is_array;
use function sprintf;

class FileIndexer implements IndexerInterface
{
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
     * @var MetadataService
     */
    private $metadataService;

    /**
     * File Indexer constructor
     *
     * @param LoggerInterface             $logger
     * @param ResourceFactory             $resourceFactory
     * @param PictureRepository           $pictureRepository
     * @param ImageVariantService         $imageVariantService
     * @param MetadataService             $metadataService
     * @param PersistenceManagerInterface $persistenceManager
     */
    public function __construct(
        LoggerInterface $logger,
        ResourceFactory $resourceFactory,
        PictureRepository $pictureRepository,
        ImageVariantService $imageVariantService,
        MetadataService $metadataService,
        PersistenceManagerInterface $persistenceManager
    ) {
        $this->resourceFactory = $resourceFactory;
        $this->pictureRepository = $pictureRepository;
        $this->imageVariantService = $imageVariantService;
        $this->logger = $logger;
        $this->persistenceManager = $persistenceManager;
        $this->metadataService = $metadataService;
    }

    public function index(IndexerParameterInterface $parameter, ...$additional): Result
    {
        $file = $parameter->getInner();
        if (!($file instanceof File)) {
            throw new InvalidArgumentException('Argument for index must be an instance of File');
        }
        if (!isset($additional[0]) || !is_array($additional[0])) {
            throw new InvalidArgumentException('Argument 2 must be an array of Variant Configurations');
        }
        $variantConfigurations = $additional[0];

        $storageException = StorageDriverTypeException::assertSupportedDriverType($file->getStorage());
        if (null !== $storageException) {
            return new Result\Err($storageException);
        }
        if ($this->fileNeedsIndexing($file, $variantConfigurations)) {
            $this->logger->debug(sprintf('File %s needs to be (re)indexed', $file->getPublicUrl()));

            $this->buildVariants($file, $variantConfigurations);

            return $this->buildOrUpdatePicture($file, $variantConfigurations);
        } else {
            $this->logger->debug('No reindexing necessary');

            return new Result\Ok('No reindexing necessary');
        }
    }

    /**
     * Determine if the given File needs to be indexed
     *
     * @param File                   $file
     * @param VariantConfiguration[] $variantConfigurations
     * @return bool
     */
    public function fileNeedsIndexing(File $file, array $variantConfigurations): bool
    {
        $picture = $this->pictureRepository->findByFile($file);
        if ($picture === null) {
            return true;
        }

        if ($picture->getFileHash() !== $file->getSha1()) {
            return true;
        }

        foreach ($variantConfigurations as $configuration) {
            $variantFileExists = $configuration->getFolder()->hasFile($file->getName());
            if (!$variantFileExists) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param File  $file
     * @param array $variantConfigurations
     */
    private function buildVariants(File $file, array $variantConfigurations)
    {
        $this->imageVariantService->buildVariantsForImage($file, $variantConfigurations);
    }

    private function buildOrUpdatePicture(File $file, array $variantConfigurations): Result
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
        $indexEntry->setVariantConfigurations($variantConfigurations);

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
        $metadata = $this->metadataService->extractMetadata($file);
        $picture->setByline($metadata->getByline());
        $picture->setHeadline($metadata->getHeadline());
        $picture->setCaption($metadata->getCaption());
        $picture->setCopyrightString($metadata->getCopyrightString());
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
