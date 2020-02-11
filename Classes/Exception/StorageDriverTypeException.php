<?php
declare(strict_types=1);

namespace Iresults\Pictures\Exception;

use RuntimeException;
use TYPO3\CMS\Core\Resource\ResourceStorageInterface;
use function sprintf;

class StorageDriverTypeException extends RuntimeException
{
    /**
     * @param ResourceStorageInterface $storage
     * @return static|null
     */
    public static function assertSupportedDriverType(ResourceStorageInterface $storage)
    {
        if ($storage->getDriverType() !== 'Local') {
            return new static(
                sprintf(
                    'Unsupported driver type %s. Only local file storage is support',
                    $storage->getDriverType()
                )
            );
        } else {
            return null;
        }
    }
}
