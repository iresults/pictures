<?php
declare(strict_types=1);

namespace Iresults\Pictures\Command;

use Iresults\Pictures\Domain\Model\Album;
use Iresults\Pictures\Domain\Repository\AlbumRepository;
use Iresults\Pictures\Domain\Repository\PictureRepository;
use Iresults\Pictures\Helper\QuerySettingsHelper;
use Iresults\Pictures\Indexer\AlbumIndexer;
use Iresults\Pictures\Indexer\AlbumIndexerParameter;
use Iresults\Pictures\Indexer\FileIndexer;
use Iresults\Pictures\Service\MetadataService;
use Iresults\Pictures\Service\ImageVariantService;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use function sprintf;

class SyncCommand extends Command
{
    use OutputHelperTrait;

    protected function configure()
    {
        $this->setDescription('Update Album indexes')
            ->setHelp('Update the indexes and thumbnails of all Albums');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $albumRepository = $this->getObjectManager()->get(AlbumRepository::class);
        QuerySettingsHelper::makeRepositoryIgnoreStoragePage($albumRepository);
        $logger = $this->getLogger($output);
        $albumIndex = $this->getAlbumIndex($logger);

        foreach ($albumRepository->findAll() as $album) {
            $output->writeln(
                sprintf(
                    '<bg=green;fg=white;options=bold>Update index of album #%d: %s</>',
                    $album->getUid(),
                    $album->getTitle()
                )
            );
            /** @var Album $album */
            $result = $albumIndex->index(new AlbumIndexerParameter($album));
            if ($result->isErr()) {
                $this->outputResult($output, $result);
            } else {
                $this->outputResultCollection($output, $result->ok()->unwrap());
            }
        }
    }

    private function getAlbumIndex(LoggerInterface $logger): AlbumIndexer
    {
        $om = $this->getObjectManager();

        return new AlbumIndexer(
            $logger,
            $om->get(ResourceFactory::class),
            $this->getFileIndexService($logger)
        );
    }

    private function getFileIndexService(LoggerInterface $logger): FileIndexer
    {
        $om = $this->getObjectManager();
        $pictureRepository = $om->get(PictureRepository::class);
        QuerySettingsHelper::makeRepositoryIgnoreStoragePage($pictureRepository);

        return new FileIndexer(
            $logger,
            $om->get(ResourceFactory::class),
            $pictureRepository,
            $om->get(ImageVariantService::class),
            $om->get(MetadataService::class),
            $om->get(PersistenceManagerInterface::class)
        );
    }

    private function getObjectManager(): ObjectManagerInterface
    {
        return GeneralUtility::makeInstance(ObjectManager::class);
    }

    /**
     * @param OutputInterface $output
     * @return ConsoleLogger
     */
    private function getLogger(OutputInterface $output): ConsoleLogger
    {
        $verbosityLevelMap = [
            LogLevel::DEBUG  => OutputInterface::VERBOSITY_VERBOSE,
            LogLevel::NOTICE => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::INFO   => OutputInterface::VERBOSITY_NORMAL,
        ];

        $formatLevelMap = [
            LogLevel::CRITICAL => ConsoleLogger::ERROR,
            LogLevel::DEBUG    => ConsoleLogger::INFO,
        ];

        return new ConsoleLogger($output, $verbosityLevelMap, $formatLevelMap);
    }
}
