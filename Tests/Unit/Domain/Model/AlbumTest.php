<?php
namespace Iresults\Pictures\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Andreas Thurnheer-Meier <tma@iresults.li>
 * @author Daniel Corn <cod@iresults.li>
 */
class AlbumTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Iresults\Pictures\Domain\Model\Album
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Iresults\Pictures\Domain\Model\Album();
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
    public function getStorageReturnsInitialValueForInt()
    {
        self::assertSame(
            0,
            $this->subject->getStorage()
        );
    }

    /**
     * @test
     */
    public function setStorageForIntSetsStorage()
    {
        $this->subject->setStorage(12);

        self::assertAttributeEquals(
            12,
            'storage',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getFolderReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getFolder()
        );
    }

    /**
     * @test
     */
    public function setFolderForStringSetsFolder()
    {
        $this->subject->setFolder('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'folder',
            $this->subject
        );
    }
}
