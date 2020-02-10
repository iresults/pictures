<?php
declare(strict_types=1);

namespace Iresults\Pictures\Service;

use Exception;
use Iresults\Pictures\Domain\ValueObject\VariantConfiguration;
use Prewk\Result;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Service\ImageService as ExtbaseImageService;

class ImageVariantService
{
    /**
     * @param File                   $file
     * @param VariantConfiguration[] $configurations
     * @return Result[]
     */
    public function buildVariantsForImage(File $file, array $configurations): array
    {
        $results = [];
        foreach ($configurations as $configuration) {
            $results[] = $this->buildVariantForImage($file, $configuration);
        }

        return $results;
    }

    /**
     * @param File                 $file
     * @param VariantConfiguration $configuration
     * @return Result
     */
    public function buildVariantForImage(File $file, VariantConfiguration $configuration): Result
    {
        $destination = $configuration->getFolder();
        $result = $this->resizeImage($file, $configuration);
        if ($result->isErr()) {
            return $result;
        }
        /** @var ProcessedFile $processedFile */
        $processedFile = $result->ok()->unwrap();

        try {
            $destination
                ->createFile($file->getName())
                ->setContents($processedFile->getContents());
        } catch (Exception $exception) {
            return new Result\Err($exception);
        }

        return new Result\Ok($processedFile);
    }

    /**
     * Resize the image
     *
     * @param FileInterface        $file
     * @param VariantConfiguration $configuration
     * @return Result Result containing the resized Processed Image or an exception
     */
    private function resizeImage(FileInterface $file, VariantConfiguration $configuration): Result
    {
        $crop = $configuration->getCrop();
        if ($crop === null) {
            $crop = $file instanceof FileReference ? $file->getProperty('crop') : null;
        }

        $processingInstructions = [
            'width'     => $configuration->getWidth(),
            'height'    => $configuration->getHeight(),
            'minWidth'  => $configuration->getMinWidth(),
            'minHeight' => $configuration->getMinHeight(),
            'maxWidth'  => $configuration->getMaxWidth(),
            'maxHeight' => $configuration->getMaxHeight(),
            'crop'      => $crop,
        ];
        try {
            return new Result\Ok($this->getImageService()->applyProcessingInstructions($file, $processingInstructions));
        } catch (Exception $exception) {
            return new Result\Err($exception);
        }
    }

    /**
     * Return an instance of ImageService using object manager
     *
     * @return ExtbaseImageService
     */
    private function getImageService()
    {
        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        return $objectManager->get(ExtbaseImageService::class);
    }
    //    private function prepareVariantFolder(string $destination)
    //    {
    //        if (!is_dir($destination)) {
    //            try {
    //                GeneralUtility::mkdir_deep($destination);
    //            } catch (Exception $exception) {
    //            }
    //        }
    //        if (!is_writable($destination)) {
    //            throw new IoException(sprintf('Destination directory "%s" is not writable', $destination));
    //        }
    //    }
    //
    //    private function getVariantFolder(VariantConfiguration $configuration)
    //    {
    //        return PATH_site . '/typo3temp/ir_pictures/' . $configuration->getId();
    //    }
}
