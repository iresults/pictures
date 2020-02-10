<?php
declare(strict_types=1);

namespace Iresults\Pictures\Domain\Model;

use TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Meta data for a picture
 */
class Picture extends AbstractEntity
{
    /**
     * Title
     *
     * @var string
     * @validate NotEmpty
     */
    protected $title = '';

    /**
     * Headline
     *
     * @var string
     */
    protected $headline = '';

    /**
     * Caption
     *
     * @var string
     */
    protected $caption = '';

    /**
     * Byline
     *
     * @var string
     */
    protected $byline = '';

    /**
     * Copyright
     *
     * @var string
     */
    protected $copyrightString = '';

    /**
     * Underlying file's UID
     *
     * @var int
     * @validate NotEmpty
     */
    protected $fileUid;

    /**
     * sha1 hash
     *
     * @var string
     */
    protected $fileHash = '';

    /**
     * @var File
     */
    protected $fileInstance;

    /**
     * Picture constructor
     *
     * @param File   $fileInstance
     * @param string $title
     * @param string $headline
     * @param string $caption
     * @param string $byline
     * @param string $copyright
     */
    public function __construct(
        File $fileInstance,
        string $title = '',
        string $headline = '',
        string $caption = '',
        string $byline = '',
        string $copyright = ''
    ) {
        $this->title = $title;
        $this->headline = $headline;
        $this->caption = $caption;
        $this->byline = $byline;
        $this->copyrightString = $copyright;
        $this->setFile($fileInstance);
    }

    /**
     * Return the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Return the headline
     *
     * @return string $headline
     */
    public function getHeadline()
    {
        return $this->headline;
    }

    /**
     * Return the caption
     *
     * @return string $caption
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Return the byline
     *
     * @return string $byline
     */
    public function getByline()
    {
        return $this->byline;
    }

    /**
     * Return the copyright string
     *
     * @return string $copyrightString
     */
    public function getCopyrightString()
    {
        return $this->copyrightString;
    }

    /**
     * Return the sha1 hash of the indexed file
     *
     * @return string
     */
    public function getFileHash(): string
    {
        return $this->fileHash;
    }

    /**
     * Return the underlying file's identifier
     *
     * @return int
     */
    public function getFileUid()
    {
        return $this->fileUid;
    }

    /**
     * Return the file
     *
     * @return File|null $file
     */
    public function getFile()
    {
        if (!$this->fileInstance) {
            try {
                $this->fileInstance = ResourceFactory::getInstance()->getFileObject($this->fileUid);
            } catch (FileDoesNotExistException $e) {
            }
        }

        return $this->fileInstance;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @param string $headline
     */
    public function setHeadline(string $headline)
    {
        $this->headline = $headline;
    }

    /**
     * @param string $caption
     */
    public function setCaption(string $caption)
    {
        $this->caption = $caption;
    }

    /**
     * @param string $byline
     */
    public function setByline(string $byline)
    {
        $this->byline = $byline;
    }

    /**
     * @param string $copyrightString
     */
    public function setCopyrightString(string $copyrightString)
    {
        $this->copyrightString = $copyrightString;
    }

    /**
     * @param File $file
     */
    public function setFile(File $file)
    {
        $this->fileInstance = $file;
        $this->fileUid = $file->getUid();
        $this->fileHash = $file->getSha1();
    }
}
