<?php
declare(strict_types=1);

namespace Iresults\Pictures\Indexer;

use Exception;
use InvalidArgumentException;
use Iresults\Pictures\Domain\Model\Album;
use Iresults\Pictures\Exception\StorageDriverTypeException;
use Prewk\Result;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\ResourceFactory;

class AlbumIndexer implements IndexerInterface
{
    /**
     * @var ResourceFactory
     */
    private $resourceFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var FileIndexer
     */
    private $fileIndexService;

    /**
     * Album indexer constructor
     *
     * @param LoggerInterface $logger
     * @param ResourceFactory $resourceFactory
     * @param FileIndexer     $fileIndexService
     */
    public function __construct(
        LoggerInterface $logger,
        ResourceFactory $resourceFactory,
        FileIndexer $fileIndexService
    ) {
        $this->resourceFactory = $resourceFactory;
        $this->logger = $logger;
        $this->fileIndexService = $fileIndexService;
    }

    public function index(IndexerParameterInterface $parameter, ...$additional): Result
    {
        $album = $parameter->getInner();
        if (!($album instanceof Album)) {
            throw new InvalidArgumentException('Argument for index must be an instance of Album');
        }
        $result = $this->getFilesOfAlbum($album);
        if ($result->isErr()) {
            return $result;
        }
        $files = $result->ok()->unwrap();
        $fileIndexResults = [];
        foreach ($files as $file) {
            /** @var File $file */
            $fileIndexResults[] = $this->fileIndexService->index(
                new FileIndexerParameter($file),
                $album->getVariantConfigurations()
            );
        }

        return new Result\Ok($fileIndexResults);
    }

    /**
     * @param Album $album
     * @return Result
     */
    private function getFilesOfAlbum(Album $album): Result
    {
        $storageObject = $this->resourceFactory->getStorageObject($album->getStorage());
        $storageException = StorageDriverTypeException::assertSupportedDriverType($storageObject);
        if (null !== $storageException) {
            return new Result\Err($storageException);
        }
        try {
            $folder = $storageObject->getFolder($album->getFolder());
        } catch (Exception $exception) {
            return new Result\Err($exception);
        }
        try {
            $files = $storageObject->getFilesInFolder($folder);
        } catch (Exception $exception) {
            return new Result\Err($exception);
        }

        return new Result\Ok($files);
    }
}
