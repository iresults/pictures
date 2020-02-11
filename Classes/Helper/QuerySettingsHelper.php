<?php
declare(strict_types=1);

namespace Iresults\Pictures\Helper;

use TYPO3\CMS\Extbase\Persistence\RepositoryInterface;

class QuerySettingsHelper
{
    /**
     * Update the repository to not respect the storage page
     *
     * @param RepositoryInterface $repository
     * @return RepositoryInterface
     */
    public static function makeRepositoryIgnoreStoragePage(RepositoryInterface $repository): RepositoryInterface
    {
        $querySettings = $repository->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $repository->setDefaultQuerySettings($querySettings);

        return $repository;
    }
}
