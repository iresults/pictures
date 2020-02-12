<?php
declare(strict_types=1);

namespace Iresults\Pictures\Controller;

use Iresults\Pictures\Domain\Model\Album;
use Iresults\Pictures\Helper\QuerySettingsHelper;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * AlbumController
 */
class AlbumController extends ActionController
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
     *
     * @param Album|null $album
     * @return string
     */
    public function showAction(Album $album = null)
    {
        $calledFromList = false;
        if ($album) {
            $calledFromList = true;
        } elseif (isset($this->settings['album'])) {
            $album = $this->albumRepository->findByUid((int)$this->settings['album']);
        }
        if (!$album) {
            return '<div class="alert alert-danger">No album selected</div>';
        }

        $this->view->assign('album', $album);
        $this->view->assign('showBackButton', $calledFromList);

        return $this->view->render();
    }

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
}
