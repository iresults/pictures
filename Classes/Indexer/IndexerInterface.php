<?php
declare(strict_types=1);

namespace Iresults\Pictures\Indexer;

use Prewk\Result;

/**
 * Interface IndexerInterface
 *
 * @template U
 * @template E of \Exception
 */
interface IndexerInterface
{
    /**
     * Index the given input instance
     *
     * @param IndexerParameterInterface $instance
     * @param mixed                     ...$additional
     * @return Result
     * @psalm-return Result<U, E>
     */
    public function index(IndexerParameterInterface $instance, ...$additional): Result;
}
