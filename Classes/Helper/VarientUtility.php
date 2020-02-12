<?php
declare(strict_types=1);

namespace Iresults\Pictures\Helper;

use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\ResourceFactory;

class VarientUtility
{
    const FOLDER_NAME = 'ir_pictures';

    /**
     * Return the parent Folder for Variant files
     *
     * @return Folder
     */
    public static function getVariantParentFolder()
    {
        $storage = ResourceFactory::getInstance()->getDefaultStorage();
        if ($storage->hasFolder(self::FOLDER_NAME)) {
            return $storage->getFolder(self::FOLDER_NAME);
        } else {
            return $storage->createFolder(self::FOLDER_NAME);
        }
    }
}
