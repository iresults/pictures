<?php
declare(strict_types=1);

namespace Iresults\Pictures\Indexer;

use Iresults\Pictures\Domain\Model\Album;

class AlbumIndexerParameter implements IndexerParameterInterface
{
    /**
     * @var Album
     */
    private $inner;

    public function __construct(Album $album)
    {
        $this->inner = $album;
    }

    /**
     * @return Album
     */
    public function getInner()
    {
        return $this->inner;
    }
}
