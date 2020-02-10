<?php
declare(strict_types=1);

namespace Iresults\Pictures\Domain\ValueObject;

use TYPO3\CMS\Core\Resource\Folder;

/**
 * Configuration for an image variant
 */
class VariantConfiguration
{
    /**
     * @var string
     */
    private $width;

    /**
     * @var string
     */
    private $height;

    /**
     * @var int
     */
    private $minWidth;

    /**
     * @var int
     */
    private $minHeight;

    /**
     * @var int
     */
    private $maxWidth;

    /**
     * @var int
     */
    private $maxHeight;

    /**
     * @var bool|string|null
     */
    private $crop;

    /**
     * @var Folder
     */
    private $parentFolder;

    /**
     * Variant Configuration constructor
     *
     * @param Folder      $parentFolder Parent folder in which the Folder for this Variant will be created
     * @param string      $width        Width of the image. This can be a numeric value representing the fixed width of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     * @param string      $height       Height of the image. This can be a numeric value representing the fixed height of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     * @param int         $maxWidth     Maximum width of the image
     * @param int         $maxHeight    Maximum height of the image
     * @param int         $minWidth     Minimum width of the image
     * @param int         $minHeight    Minimum height of the image
     * @param string|bool $crop         Overrule cropping of image (setting to FALSE disables the cropping set in FileReference)
     */
    public function __construct(
        Folder $parentFolder,
        $width,
        $height,
        int $maxWidth = null,
        int $maxHeight = null,
        int $minWidth = null,
        int $minHeight = null,
        $crop = null
    ) {
        $this->parentFolder = $parentFolder;
        $this->width = $width;
        $this->height = $height;
        $this->minWidth = $minWidth;
        $this->minHeight = $minHeight;
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
        $this->crop = $crop;
    }

    /**
     * Build a Variant Configuration
     *
     * @param Folder $parentFolder Parent folder in which the Folder for this Variant will be created
     * @param string $width        Width of the image. This can be a numeric value representing the fixed width of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     * @param string $height       Height of the image. This can be a numeric value representing the fixed height of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     * @return VariantConfiguration
     */
    public static function build(
        Folder $parentFolder,
        $width = null,
        $height = null
    ): VariantConfiguration {
        return new static($parentFolder, $width, $height);
    }

    /**
     * Width of the image. This can be a numeric value representing the fixed width of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     *
     * @return string
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Height of the image. This can be a numeric value representing the fixed height of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     *
     * @return string
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Minimum width of the image
     *
     * @return int
     */
    public function getMinWidth()
    {
        return $this->minWidth;
    }

    /**
     * Minimum height of the image
     *
     * @return int
     */
    public function getMinHeight()
    {
        return $this->minHeight;
    }

    /**
     * Maximum width of the image
     *
     * @return int
     */
    public function getMaxWidth()
    {
        return $this->maxWidth;
    }

    /**
     * Maximum height of the image
     *
     * @return int
     */
    public function getMaxHeight()
    {
        return $this->maxHeight;
    }

    /**
     * Overrule cropping of image (setting to FALSE disables the cropping set in FileReference)
     *
     * @return bool|string|null
     */
    public function getCrop()
    {
        return $this->crop;
    }

    /**
     * Return the parent folder
     *
     * @return Folder
     */
    public function getParentFolder(): Folder
    {
        return $this->parentFolder;
    }

    /**
     * Return the destination folder
     *
     * @return Folder
     */
    public function getFolder(): Folder
    {
        $parentFolder = $this->getParentFolder();
        $variantFolderName = $this->getId();
        if (!$parentFolder->hasFolder($variantFolderName)) {
            $parentFolder->createFolder($variantFolderName);
        }

        return $parentFolder->getSubfolder($variantFolderName);
    }

    /**
     * Return a string representing the Variant
     *
     * @return string
     */
    public function getId(): string
    {
        $crop = $this->crop === null ? 'auto' : ($this->crop ?? 'off');

        return $this->width . 'x' . $this->height
            . '_' . $this->minWidth . 'x' . $this->minHeight
            . '_' . $this->maxWidth . 'x' . $this->maxHeight . '_' .
            $crop;
    }
}
