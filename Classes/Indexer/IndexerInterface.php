<?php
declare(strict_types=1);

namespace Iresults\Pictures\Indexer;

use Prewk\Result;

interface IndexerInterface
{
    public function index(IndexerParameterInterface $instance, ...$additional): Result;
}
