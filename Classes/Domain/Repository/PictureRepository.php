<?php
declare(strict_types=1);

namespace Iresults\Pictures\Domain\Repository;

use Iresults\Pictures\Domain\Model\Picture;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;
use function array_map;

/**
 * The repository for Pictures
 */
class PictureRepository extends Repository
{
    /**
     * Return the Picture/Index Entry for the given File
     *
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
     * Return the Pictures/Index Entries for the given Files
     *
     * @param File[] $files
     * @return Picture[]
     */
    public function findByFiles(array $files): array
    {
        $query = $this->createQuery();
        $fileUids = array_map(
            function (File $fi) {
                return $fi->getUid();
            },
            $files
        );

        return $query
            ->matching($query->in('file_uid', $fileUids))
            ->execute()
            ->toArray();
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
