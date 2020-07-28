<?php
declare(strict_types=1);

namespace Iresults\Pictures\Domain\ValueObject;

use Iresults\Pictures\Domain\Model\Album;
use Prewk\Result;

class AlbumResultInfo
{
    /**
     * @var Album
     */
    private $album;

    /**
     * @var string
     */
    private $message;

    /**
     * @var Result[]
     * @psalm-var Result<PictureResultInfo, \Exception>[]
     */
    private $fileIndexResults;

    /**
     * Album Result Info constructor
     *
     * @param Album  $album
     * @param string $message
     * @param array  $fileIndexResults
     * @psalm-param Result<PictureResultInfo, \Exception>[] $fileIndexResults
     */
    public function __construct(Album $album, string $message, array $fileIndexResults)
    {
        $this->album = $album;
        $this->message = $message;
        $this->fileIndexResults = $fileIndexResults;
    }

    /**
     * @return Album
     */
    public function getAlbum(): Album
    {
        return $this->album;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return Result[]
     * @psalm-return Result<PictureResultInfo, \Exception>[]
     */
    public function getFileIndexResults(): array
    {
        return $this->fileIndexResults;
    }

}
