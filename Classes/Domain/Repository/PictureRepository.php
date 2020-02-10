<?php
declare(strict_types=1);

namespace Iresults\Pictures\Domain\Repository;

use Iresults\Pictures\Domain\Model\Picture;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * The repository for Pictures
 */
class PictureRepository extends Repository
{
    /**
     * @param File $file
     * @return Picture|null
     */
    public function findByFile(File $file)
    {
        $query = $this->createQuery();
        $result = $query->matching($query->equals('file_uid', $file->getUid()))->setLimit(1)->execute();
        if ($result instanceof QueryResultInterface) {
            /** @var Picture|null $picture */
            $picture = $result->getFirst();

            return $picture;
        }
        if (is_array($result)) {
            return isset($result[0]) ? $result[0] : null;
        }

        return null;
    }

    /**
     * Return if an entry for the given file exists
     *
     * @param File $file
     * @return bool
     */
    public function containsEntryForFile(File $file): bool
    {
        $query = $this->createQuery();
        $result = $query->matching($query->equals('file_uid', $file->getUid()))->setLimit(1)->execute();

        return $result->count() > 0;
    }
}
