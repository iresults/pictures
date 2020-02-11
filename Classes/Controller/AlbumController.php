<?php

namespace Iresults\Pictures\Controller;

use Iresults\Pictures\Helper\QuerySettingsHelper;

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

    protected function initializeAction()
    {
        parent::initializeAction();
        QuerySettingsHelper::makeRepositoryIgnoreStoragePage($this->albumRepository);
    }

    /**
     * action show
     */
    public function showAction()
    {
        if (isset($this->settings['album'])) {
            $album = $this->albumRepository->findByUid((int)$this->settings['album']);
            $this->view->assign('album', $album);

            return $this->view->render();
        } else {
            return '<div class="alert alert-danger">No album selected</div>';
        }
    }
}
