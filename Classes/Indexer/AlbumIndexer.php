<?php
declare(strict_types=1);

namespace Iresults\Pictures\Indexer;

use Exception;
use InvalidArgumentException;
use Iresults\Pictures\Domain\Model\Album;
use Iresults\Pictures\Domain\ValueObject\AlbumResultInfo;
use Iresults\Pictures\Exception\StorageDriverTypeException;
use Prewk\Result;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\ResourceFactory;

/**
 * Album Indexer
 *
 * @template   E of \Exception
 * @implements IndexerInterface<AlbumResultInfo, E>
 */
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

    /**
     * @template     E
     * @param IndexerParameterInterface $parameter
     * @param mixed                     ...$additional
     * @return Result
     * @psalm-return Result<Album, E>
     */
    public function index(IndexerParameterInterface $parameter, ...$additional): Result
    {
        $album = $parameter->getInner();
        if (!($album instanceof Album)) {
            throw new InvalidArgumentException('Argument for index must be an instance of Album');
        }
        $filesOfAlbum = $this->getFilesOfAlbum($album);
        if ($filesOfAlbum->isErr()) {
            return $filesOfAlbum;
        }
        $files = $filesOfAlbum->ok()->unwrap();
        $fileIndexResults = [];
        $pictures = [];
        foreach ($files as $file) {
            /** @var File $file */
            /** @psalm-var Result<\Iresults\Pictures\Domain\ValueObject\PictureResultInfo, E> $result */
            $result = $this->fileIndexService->index(
                new FileIndexerParameter($file),
                $album->getVariantConfigurations()
            );
            $fileIndexResults[] = $result;
            if ($result->isOk()) {
                $pictures[] = $result->ok()->unwrap();
            }
        }

        $album->setPictures($pictures);

        return new Result\Ok(new AlbumResultInfo($album, '', $fileIndexResults));
    }

    /**
     * @template     E
     * @param Result $x
     * @psalm-param  Result<string,E> $res
     * @return Result
     * @psalm-return Result<int,E>
     */
    private function adsfa(Result $x): Result
    {
        return new Result\Ok('fdasfads');
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
