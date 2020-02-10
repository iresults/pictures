<?php
namespace Iresults\Pictures\Controller;

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
 * PictureController
 */
class PictureController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * pictureRepository
     *
     * @var \Iresults\Pictures\Domain\Repository\PictureRepository
     * @inject
     */
    protected $pictureRepository = null;

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $pictures = $this->pictureRepository->findAll();
        $this->view->assign('pictures', $pictures);
    }

    /**
     * action show
     *
     * @param \Iresults\Pictures\Domain\Model\Picture $picture
     * @return void
     */
    public function showAction(\Iresults\Pictures\Domain\Model\Picture $picture)
    {
        $this->view->assign('picture', $picture);
    }
}
