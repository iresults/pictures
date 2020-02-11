<?php
declare(strict_types=1);

namespace Iresults\Pictures\Domain\Model;

use Exception;
use Iresults\Pictures\Domain\Repository\PictureRepository;
use Iresults\Pictures\Domain\ValueObject\VariantConfiguration;
use Iresults\Pictures\Exception\StorageDriverTypeException;
use Iresults\Pictures\Helper\QuerySettingsHelper;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use function array_map;

/**
 * Album
 */
class Album extends AbstractEntity
{
    const FOLDER_NAME = 'ir_pictures';

    /**
     * Title
     *
     * @var string
     * @validate NotEmpty
     */
    protected $title = '';

    /**
     * Storage
     *
     * @var int
     * @validate NotEmpty
     */
    protected $storage = 0;

    /**
     * Folder
     *
     * @var string
     * @validate NotEmpty
     */
    protected $folder = '';

    /**
     * @var PictureRepository
     */
    protected $pictureRepository;

    /**
     * @var ResourceFactory
     */
    protected $resourceFactory;

    /** @noinspection PhpUnused */
    public function injectPictureRepository(PictureRepository $pictureRepository)
    {
        $this->pictureRepository = QuerySettingsHelper::makeRepositoryIgnoreStoragePage(clone $pictureRepository);
    }

    /** @noinspection PhpUnused */
    public function injectResourceFactory(ResourceFactory $resourceFactory)
    {
        $this->resourceFactory = $resourceFactory;
    }

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the storage
     *
     * @return int $storage
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Returns the folder
     *
     * @return string $folder
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * @return VariantConfiguration[]
     */
    public function getVariantConfigurations(): array
    {
        $folder = $this->getVariantParentFolder();

        return [
            'big'       => VariantConfiguration::build($folder, '1600m'),
            'thumbnail' => VariantConfiguration::build($folder, '560c', '560c'),
        ];
    }

    /**
     * @return Folder
     */
    private function getVariantParentFolder()
    {
        $storage = $this->resourceFactory->getDefaultStorage();
        if ($storage->hasFolder(self::FOLDER_NAME)) {
            return $storage->getFolder(self::FOLDER_NAME);
        } else {
            return $storage->createFolder(self::FOLDER_NAME);
        }
    }

    /**
     * @return File[]
     */
    private function getFiles()
    {
        $storageObject = $this->resourceFactory->getStorageObject($this->getStorage());
        $storageException = StorageDriverTypeException::assertSupportedDriverType($storageObject);
        if (null !== $storageException) {
            throw $storageException;
        }
        try {
            $folder = $storageObject->getFolder($this->getFolder());

            return $storageObject->getFilesInFolder($folder);
        } catch (Exception $exception) {
            return [];
        }
    }
}
