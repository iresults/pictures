<?php
declare(strict_types=1);

namespace Iresults\Pictures\Service;

use Iresults\Pictures\Domain\ValueObject\Metadata;
use Iresults\Pictures\Exception\MetadataServiceException;
use TYPO3\CMS\Core\Resource\File;
use function file_exists;
use function getimagesize;
use function implode;
use function iptcparse;
use function is_readable;
use function sprintf;
use const PATH_site;

class MetadataService
{
    /**
     * Extract metadata from the given File
     *
     * @param File $file
     * @return Metadata
     */
    public function extractMetadata(File $file): Metadata
    {
        $info = $this->extractIPTCData($file);

        return new Metadata(
            $info['DocumentTitle'],
            $info['AuthorByline'],
            $info['Caption'],
            $info['Copyright']
        );
    }

    /**
     * @param File $file
     * @return array
     */
    private function extractIPTCData(File $file): array
    {
        $info = [];
        $absoluteFilePath = PATH_site . $file->getPublicUrl();
        if (!is_readable($absoluteFilePath)) {
            if (file_exists($absoluteFilePath)) {
                throw new MetadataServiceException(sprintf('File %s is not readable', $absoluteFilePath));
            } else {
                throw new MetadataServiceException(sprintf('File %s does not exist', $absoluteFilePath));
            }
        }

        getimagesize($absoluteFilePath, $info);
        if (!isset($info['APP13'])) {
            throw new MetadataServiceException(
                sprintf('Could not fetch IPTC metadata from file %s', $absoluteFilePath)
            );
        }
        $iptc = iptcparse($info['APP13']);
        if (false === $iptc) {
            throw new MetadataServiceException(
                sprintf('Could not fetch IPTC metadata from file %s', $absoluteFilePath)
            );
        }

        $iptcHeaderArray = [
            '2#005' => 'DocumentTitle',
            '2#010' => 'Urgency',
            '2#015' => 'Category',
            '2#020' => 'Subcategories',
            '2#040' => 'SpecialInstructions',
            '2#055' => 'CreationDate',
            '2#080' => 'AuthorByline',
            '2#085' => 'AuthorTitle',
            '2#090' => 'City',
            '2#095' => 'State',
            '2#101' => 'Country',
            '2#103' => 'OTR',
            '2#105' => 'Headline',
            '2#110' => 'Source',
            '2#115' => 'PhotoSource',
            '2#116' => 'Copyright',
            '2#120' => 'Caption',
            '2#122' => 'CaptionWriter',
        ];

        $preparedData = [];
        foreach ($iptc as $originalKey => $value) {
            $preparedData[$originalKey] = $value;
            $preparedData[$iptcHeaderArray[$originalKey]] = implode(' ', $value);
        }

        return $preparedData;
    }
}
