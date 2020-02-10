<?php
namespace Iresults\Pictures\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Andreas Thurnheer-Meier <tma@iresults.li>
 * @author Daniel Corn <cod@iresults.li>
 */
class PictureTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Iresults\Pictures\Domain\Model\Picture
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Iresults\Pictures\Domain\Model\Picture();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getTitleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getTitle()
        );
    }

    /**
     * @test
     */
    public function setTitleForStringSetsTitle()
    {
        $this->subject->setTitle('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'title',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getHeadlineReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getHeadline()
        );
    }

    /**
     * @test
     */
    public function setHeadlineForStringSetsHeadline()
    {
        $this->subject->setHeadline('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'headline',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getCaptionReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCaption()
        );
    }

    /**
     * @test
     */
    public function setCaptionForStringSetsCaption()
    {
        $this->subject->setCaption('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'caption',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getBylineReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getByline()
        );
    }

    /**
     * @test
     */
    public function setBylineForStringSetsByline()
    {
        $this->subject->setByline('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'byline',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getCopyrightStringReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCopyrightString()
        );
    }

    /**
     * @test
     */
    public function setCopyrightStringForStringSetsCopyrightString()
    {
        $this->subject->setCopyrightString('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'copyrightString',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getFileReturnsInitialValueForFileReference()
    {
        self::assertEquals(
            null,
            $this->subject->getFile()
        );
    }

    /**
     * @test
     */
    public function setFileForFileReferenceSetsFile()
    {
        $fileReferenceFixture = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
        $this->subject->setFile($fileReferenceFixture);

        self::assertAttributeEquals(
            $fileReferenceFixture,
            'file',
            $this->subject
        );
    }
}
