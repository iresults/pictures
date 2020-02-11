<?php
declare(strict_types=1);

namespace Iresults\Pictures\Indexer;

use TYPO3\CMS\Core\Resource\File;

class FileIndexerParameter implements IndexerParameterInterface
{
    /**
     * @var File
     */
    private $inner;

    public function __construct(File $file)
    {
        $this->inner = $file;
    }

    /**
     * @return File
     */
    public function getInner()
    {
        return $this->inner;
    }
}
