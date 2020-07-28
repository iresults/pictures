<?php
declare(strict_types=1);

namespace Iresults\Pictures\Domain\ValueObject;

use Iresults\Pictures\Domain\Model\Picture;

class PictureResultInfo
{
    /**
     * @var Picture
     */
    private $picture;

    /**
     * @var string
     */
    private $message;

    /**
     * PictureResultInfo constructor.
     *
     * @param Picture $picture
     * @param string  $message
     */
    public function __construct(Picture $picture, string $message)
    {
        $this->picture = $picture;
        $this->message = $message;
    }

    /**
     * @return Picture
     */
    public function getPicture(): Picture
    {
        return $this->picture;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
