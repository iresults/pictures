<?php
declare(strict_types=1);

namespace Iresults\Pictures\Domain\Model;

use Exception;
use InvalidArgumentException;
use Iresults\Pictures\Domain\Repository\PictureRepository;
use Iresults\Pictures\Domain\ValueObject\VariantConfiguration;
use Iresults\Pictures\Exception\StorageDriverTypeException;
use Iresults\Pictures\Helper\QuerySettingsHelper;
use Iresults\Pictures\Helper\VarientUtility;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use function array_map;
use function is_array;

/**
 * Album
 */
class Album extends AbstractEntity
{
    /**
     * @var PictureRepository
     */
    protected $pictureRepository = null;

    /**
     * @var ResourceFactory
     */
    protected $resourceFactory = null;

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
     * Poster image of the Album
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @cascade      remove
     * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
     */
    protected $poster = null;

    /**
     * Description
     *
     * @var string
     */
    protected $description = '';

    /**
     * Pictures in this album
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Iresults\Pictures\Domain\Model\Picture>
     */
    protected $pictures = null;

    /**
     * @noinspection PhpUnused
     * @param PictureRepository $pictureRepository
     */
    public function injectPictureRepository(PictureRepository $pictureRepository)
    {
        $this->pictureRepository = QuerySettingsHelper::makeRepositoryIgnoreStoragePage(clone $pictureRepository);
    }

    /**
     * @noinspection PhpUnused
     * @param ResourceFactory $resourceFactory
     */
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
     * Return the Pictures/Index Entries belonging to this Album
     *
     * @return Picture[]
     */
    public function getPictures()
    {
        $variantConfigurations = $this->getVariantConfigurations();

        return array_map(
            function (Picture $picture) use ($variantConfigurations) {
                $picture->setVariantConfigurations($variantConfigurations);

                return $picture;
            },
            $this->pictureRepository->findByFiles($this->getFiles())
        );
    }

    /**
     * @return VariantConfiguration[]
     */
    public function getVariantConfigurations(): array
    {
        $folder = VarientUtility::getVariantParentFolder();

        return [
            'big'       => VariantConfiguration::build($folder, '1600m'),
            'thumbnail' => VariantConfiguration::build($folder, '560c', '560c'),
        ];
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

    /**
     * Returns the poster
     *
     * @return FileReference $poster
     */
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * Sets the poster
     *
     * @param FileReference $poster
     * @return void
     */
    public function setPoster(FileReference $poster)
    {
        $this->poster = $poster;
    }

    /**
     * Returns the description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->pictures = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Adds a Picture
     *
     * @param \Iresults\Pictures\Domain\Model\Picture $picture
     * @return void
     */
    public function addPicture(\Iresults\Pictures\Domain\Model\Picture $picture)
    {
        $this->pictures->attach($picture);
    }

    /**
     * Removes a Picture
     *
     * @param \Iresults\Pictures\Domain\Model\Picture $pictureToRemove The Picture to be removed
     * @return void
     */
    public function removePicture(\Iresults\Pictures\Domain\Model\Picture $pictureToRemove)
    {
        $this->pictures->detach($pictureToRemove);
    }

    /**
     * Sets the pictures
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Iresults\Pictures\Domain\Model\Picture>|Picture[] $pictures
     * @return void
     */
    public function setPictures($pictures)
    {
        if ($pictures instanceof ObjectStorage) {
            $this->pictures = $pictures;
        } elseif (is_array($pictures)) {
            $objectStorage = new ObjectStorage();
            foreach ($pictures as $picture) {
                $objectStorage->attach($picture);
            }
            $this->pictures = $objectStorage;
        } else {
            throw new InvalidArgumentException('Argument "pictures" must be iterable');
        }
    }
}
