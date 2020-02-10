<?php
declare(strict_types=1);

namespace Iresults\Pictures\Command;

use Iresults\Pictures\Domain\Repository\AlbumRepository;
use Iresults\Pictures\Domain\Repository\PictureRepository;
use Iresults\Pictures\Indexer\AlbumIndex;
use Iresults\Pictures\Indexer\FileIndex;
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
use TYPO3\CMS\Extbase\Persistence\RepositoryInterface;

class SyncCommand extends Command
{
    use OutputHelperTrait;

    protected function configure()
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $albumRepository = $this->getObjectManager()->get(AlbumRepository::class);
        $this->prepareRepository($albumRepository);
        foreach ($albumRepository->findAll() as $album) {
            $logger = $this->getLogger($output);
            $result = $this->getAlbumIndexService($logger)->index($album);
            if ($result->isErr()) {
                $this->outputResult($output, $result);
            } else {
                $this->outputResultCollection($output, $result->ok()->unwrap());
            }
        }
    }

    private function prepareRepository(RepositoryInterface $repository)
    {
        $querySettings = $repository->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $repository->setDefaultQuerySettings($querySettings);
    }

    private function getAlbumIndexService(LoggerInterface $logger): AlbumIndex
    {
        $om = $this->getObjectManager();
        $pictureRepository = $om->get(PictureRepository::class);
        $this->prepareRepository($pictureRepository);

        return new AlbumIndex(
            $logger,
            $om->get(ResourceFactory::class),
            $pictureRepository,
            $om->get(ImageVariantService::class),
            $om->get(PersistenceManagerInterface::class),
            $this->getFileIndexService($logger)
        );
    }

    private function getFileIndexService(LoggerInterface $logger): FileIndex
    {
        $om = $this->getObjectManager();
        $pictureRepository = $om->get(PictureRepository::class);
        $this->prepareRepository($pictureRepository);

        return new FileIndex(
            $logger,
            $om->get(ResourceFactory::class),
            $pictureRepository,
            $om->get(ImageVariantService::class),
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
