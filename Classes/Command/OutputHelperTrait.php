<?php
declare(strict_types=1);

namespace Iresults\Pictures\Command;

use Prewk\Result;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

trait OutputHelperTrait
{
    /**
     * @inheritDoc
     * @see \Symfony\Component\Console\Command\Command::getHelper
     */
    abstract public function getHelper($name);

    /**
     * Output the given Result
     *
     * @param OutputInterface $output
     * @param Result          $result
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function outputResult(OutputInterface $output, Result $result)
    {
        if ($result->isOk()) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $output->writeln('<info>' . $result->unwrap() . '</info>');
        } else {
            /** @var Throwable $error */
            $error = $result->err()->unwrap();

            /** @var FormatterHelper $formatter */
            $formatter = $this->getHelper('formatter');
            $errorMessages = ['ERROR', $error->getMessage()];
            $formattedBlock = $formatter->formatBlock($errorMessages, 'error', true);
            $output->writeln($formattedBlock);
            $output->writeln('');
            $output->writeln($error->__toString());
        }
    }

    /**
     * Output all Results in the given collection
     *
     * @param OutputInterface $output
     * @param Result[]        $resultCollection
     */
    public function outputResultCollection(OutputInterface $output, array $resultCollection)
    {
        foreach ($resultCollection as $result) {
            $this->outputResult($output, $result);
        }
    }
}
