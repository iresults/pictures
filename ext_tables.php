<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function () {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Iresults.Pictures',
            'Pictures',
            'Pictures'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            'pictures',
            'Configuration/TypoScript',
            'Pictures'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
            'tx_pictures_domain_model_picture',
            'EXT:pictures/Resources/Private/Language/locallang_csh_tx_pictures_domain_model_picture.xlf'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
            'tx_pictures_domain_model_picture'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
            'tx_pictures_domain_model_album',
            'EXT:pictures/Resources/Private/Language/locallang_csh_tx_pictures_domain_model_album.xlf'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_pictures_domain_model_album');

        $pluginSignature = str_replace('_', '', 'pictures') . '_pictures';
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
            $pluginSignature,
            'FILE:EXT:pictures/Configuration/FlexForms/flexform_pictures.xml'
        );
    }
);
