<?php
declare(strict_types=1);

namespace Iresults\Pictures\Indexer;

/**
 * Interface for parameters passed to the Indexer implementation
 */
interface IndexerParameterInterface
{
    /**
     * Return the wrapped value
     *
     * @return mixed
     */
    public function getInner();
}
