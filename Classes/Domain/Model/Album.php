<?php
declare(strict_types=1);

namespace Iresults\Pictures\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Album
 */
class Album extends AbstractEntity
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
