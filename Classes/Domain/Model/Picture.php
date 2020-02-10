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
 * Meta data for a picture
 */
class Picture extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
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
     * file
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @cascade remove
     */
    protected $file = null;

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
     * Returns the headline
     *
     * @return string $headline
     */
    public function getHeadline()
    {
        return $this->headline;
    }

    /**
     * Sets the headline
     *
     * @param string $headline
     * @return void
     */
    public function setHeadline($headline)
    {
        $this->headline = $headline;
    }

    /**
     * Returns the caption
     *
     * @return string $caption
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Sets the caption
     *
     * @param string $caption
     * @return void
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    /**
     * Returns the byline
     *
     * @return string $byline
     */
    public function getByline()
    {
        return $this->byline;
    }

    /**
     * Sets the byline
     *
     * @param string $byline
     * @return void
     */
    public function setByline($byline)
    {
        $this->byline = $byline;
    }

    /**
     * Returns the copyrightString
     *
     * @return string $copyrightString
     */
    public function getCopyrightString()
    {
        return $this->copyrightString;
    }

    /**
     * Sets the copyrightString
     *
     * @param string $copyrightString
     * @return void
     */
    public function setCopyrightString($copyrightString)
    {
        $this->copyrightString = $copyrightString;
    }

    /**
     * Returns the file
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Sets the file
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $file
     * @return void
     */
    public function setFile(\TYPO3\CMS\Extbase\Domain\Model\FileReference $file)
    {
        $this->file = $file;
    }
}
