<?php
declare(strict_types=1);

namespace Iresults\Pictures\Indexer;

use Exception;
use InvalidArgumentException;
use Iresults\Pictures\Domain\Model\Album;
use Prewk\Result;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Resource\ResourceFactory;

class AlbumIndex implements IndexerInterface
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
     * @var FileIndex
     */
    private $fileIndexService;

    /**
     * Album indexer constructor
     *
     * @param LoggerInterface $logger
     * @param ResourceFactory $resourceFactory
     * @param FileIndex       $fileIndexService
     */
    public function __construct(
        LoggerInterface $logger,
        ResourceFactory $resourceFactory,
        FileIndex $fileIndexService
    ) {
        $this->resourceFactory = $resourceFactory;
        $this->logger = $logger;
        $this->fileIndexService = $fileIndexService;
    }

    public function index($album): Result
    {
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
            $fileIndexResults[] = $this->fileIndexService->index($file);
        }

        return new Result\Ok($fileIndexResults);
    }

    /**
     * @param Album $album
     * @return Result
     */
    private function getFilesOfAlbum(Album $album): Result
    {
        $defaultStorage = $this->resourceFactory->getStorageObject($album->getStorage());
        try {
            $folder = $defaultStorage->getFolder($album->getFolder());
        } catch (Exception $exception) {
            return new Result\Err($exception);
        }
        try {
            $files = $defaultStorage->getFilesInFolder($folder);
        } catch (Exception $exception) {
            return new Result\Err($exception);
        }

        return new Result\Ok($files);
    }
}
