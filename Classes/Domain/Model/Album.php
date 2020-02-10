<?php
namespace Iresults\Pictures\Domain\Model;

/***
 *
 * This file is part of the "Pictures" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Andreas Thurnheer-Meier <tma@iresults.li>, iresults GmbH
 *           Daniel Corn <cod@iresults.li>, iresults GmbH
 *
 ***/

/**
 * Album
 */
class Album extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Title
     *
     * @var string
     * @validate NotEmpty
     */
    protected $title = '';

    /**
     * Storage
     *
     * @var int
     * @validate NotEmpty
     */
    protected $storage = 0;

    /**
     * Folder
     *
     * @var string
     * @validate NotEmpty
     */
    protected $folder = '';

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the storage
     *
     * @return int $storage
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Sets the storage
     *
     * @param int $storage
     * @return void
     */
    public function setStorage($storage)
    {
        $this->storage = $storage;
    }

    /**
     * Returns the folder
     *
     * @return string $folder
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * Sets the folder
     *
     * @param string $folder
     * @return void
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;
    }
}
