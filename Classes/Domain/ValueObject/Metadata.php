<?php
declare(strict_types=1);

namespace Iresults\Pictures\Domain\ValueObject;

class Metadata
{
    private $byline;

    private $headline;

    private $caption;

    private $copyrightString;

    /**
     * Metadata constructor
     *
     * @param string $headline
     * @param string $byline
     * @param string $caption
     * @param string $copyrightString
     */
    public function __construct(string $headline, string $byline, string $caption, string $copyrightString)
    {
        $this->headline = $headline;
        $this->byline = $byline;
        $this->caption = $caption;
        $this->copyrightString = $copyrightString;
    }

    public function getByline(): string
    {
        return $this->byline;
    }

    public function getHeadline(): string
    {
        return $this->headline;
    }

    public function getCaption(): string
    {
        return $this->caption;
    }

    public function getCopyrightString(): string
    {
        return $this->copyrightString;
    }
}
