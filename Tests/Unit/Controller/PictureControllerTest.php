<?php
namespace Iresults\Pictures\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Andreas Thurnheer-Meier <tma@iresults.li>
 * @author Daniel Corn <cod@iresults.li>
 */
class PictureControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Iresults\Pictures\Controller\PictureController
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\Iresults\Pictures\Controller\PictureController::class)
            ->setMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllPicturesFromRepositoryAndAssignsThemToView()
    {

        $allPictures = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $pictureRepository = $this->getMockBuilder(\Iresults\Pictures\Domain\Repository\PictureRepository::class)
            ->setMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $pictureRepository->expects(self::once())->method('findAll')->will(self::returnValue($allPictures));
        $this->inject($this->subject, 'pictureRepository', $pictureRepository);

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('pictures', $allPictures);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenPictureToView()
    {
        $picture = new \Iresults\Pictures\Domain\Model\Picture();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('picture', $picture);

        $this->subject->showAction($picture);
    }
}
