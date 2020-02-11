<?php
declare(strict_types=1);

namespace Iresults\Pictures\Domain\ValueObject;

use Iresults\Pictures\Domain\Model\Picture;

/**
 * Variant is built from applying a Variant Configuration to a single Picture
 */
class Variant
{
    /**
     * @var Picture
     */
    private $picture;

    /**
     * @var VariantConfiguration
     */
    private $variantConfiguration;

    /**
     * Variant constructor
     *
     * @param Picture              $picture
     * @param VariantConfiguration $variantConfiguration
     */
    public function __construct(Picture $picture, VariantConfiguration $variantConfiguration)
    {
        $this->picture = $picture;
        $this->variantConfiguration = $variantConfiguration;
    }

    /**
     * Return the public URL to this Variant
     *
     * @return string
     */
    public function getPublicUrl(): string
    {
        $folder = $this->variantConfiguration->getFolder();

        return $folder->getPublicUrl() . $this->picture->getName();
    }

    public function __toString()
    {
        return $this->getPublicUrl();
    }
}
