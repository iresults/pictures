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
 * AlbumController
 */
class AlbumController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * albumRepository
     *
     * @var \Iresults\Pictures\Domain\Repository\AlbumRepository
     * @inject
     */
    protected $albumRepository = null;

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $albums = $this->albumRepository->findAll();
        $this->view->assign('albums', $albums);
    }

    /**
     * action show
     *
     * @param \Iresults\Pictures\Domain\Model\Album $album
     * @return void
     */
    public function showAction(\Iresults\Pictures\Domain\Model\Album $album)
    {
        $this->view->assign('album', $album);
    }
}
