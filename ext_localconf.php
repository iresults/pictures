<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Iresults.Pictures',
            'Pictures',
            [
                'Picture' => 'list, show'
            ],
            // non-cacheable actions
            [
                'Picture' => ''
            ]
        );

    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    pictures {
                        iconIdentifier = pictures-plugin-pictures
                        title = LLL:EXT:pictures/Resources/Private/Language/locallang_db.xlf:tx_pictures_pictures.name
                        description = LLL:EXT:pictures/Resources/Private/Language/locallang_db.xlf:tx_pictures_pictures.description
                        tt_content_defValues {
                            CType = list
                            list_type = pictures_pictures
                        }
                    }
                }
                show = *
            }
       }'
    );
		$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
		
			$iconRegistry->registerIcon(
				'pictures-plugin-pictures',
				\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
				['source' => 'EXT:pictures/Resources/Public/Icons/user_plugin_pictures.svg']
			);
		
    }
);
