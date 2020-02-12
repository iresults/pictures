<?php
return [
    'ctrl'      => [
        'title'                    => 'LLL:EXT:pictures/Resources/Private/Language/locallang_db.xlf:tx_pictures_domain_model_album',
        'label'                    => 'title',
        'tstamp'                   => 'tstamp',
        'crdate'                   => 'crdate',
        'cruser_id'                => 'cruser_id',
        'versioningWS'             => true,
        'languageField'            => 'sys_language_uid',
        'transOrigPointerField'    => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete'                   => 'deleted',
        'enablecolumns'            => [
            'disabled'  => 'hidden',
            'starttime' => 'starttime',
            'endtime'   => 'endtime',
        ],
        'searchFields' => 'title,storage,folder,poster,description',
        'iconfile' => 'EXT:pictures/Resources/Public/Icons/tx_pictures_domain_model_album.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, storage, folder, poster, description',
    ],
    'types'     => [
        '1' => ['showitem' => 'title, storage, folder, poster, description, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, sys_language_uid, l10n_parent, l10n_diffsource, hidden, starttime, endtime'],
    ],
    'columns'   => [
        'sys_language_uid' => [
            'exclude' => true,
            'label'   => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config'  => [
                'type'       => 'select',
                'renderType' => 'selectSingle',
                'special'    => 'languages',
                'items'      => [
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple',
                    ],
                ],
                'default'    => 0,
            ],
        ],
        'l10n_parent'      => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude'     => true,
            'label'       => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config'      => [
                'type'                => 'select',
                'renderType'          => 'selectSingle',
                'default'             => 0,
                'items'               => [
                    ['', 0],
                ],
                'foreign_table'       => 'tx_pictures_domain_model_album',
                'foreign_table_where' => 'AND tx_pictures_domain_model_album.pid=###CURRENT_PID### AND tx_pictures_domain_model_album.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource'  => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label'      => [
            'label'  => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max'  => 255,
            ],
        ],
        'hidden'           => [
            'exclude' => true,
            'label'   => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config'  => [
                'type'  => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/Resources/Private/Language/locallang_core.xlf:labels.enabled',
                    ],
                ],
            ],
        ],
        'starttime'        => [
            'exclude'   => true,
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
            'label'     => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config'    => [
                'type'       => 'input',
                'renderType' => 'inputDateTime',
                'size'       => 13,
                'eval'       => 'datetime',
                'default'    => 0,
            ],
        ],
        'endtime'          => [
            'exclude'   => true,
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
            'label'     => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config'    => [
                'type'       => 'input',
                'renderType' => 'inputDateTime',
                'size'       => 13,
                'eval'       => 'datetime',
                'default'    => 0,
                'range'      => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038),
                ],
            ],
        ],

        'title' => [
            'exclude' => false,
            'label'   => 'LLL:EXT:pictures/Resources/Private/Language/locallang_db.xlf:tx_pictures_domain_model_album.title',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
            ],
        ],

        'storage' => [
            'exclude'  => false,
            'label'    => 'LLL:EXT:pictures/Resources/Private/Language/locallang_db.xlf:tx_pictures_domain_model_album.storage',
            'onChange' => 'reload',
            'config'   => [
                'type'                => 'select',
                'renderType'          => 'selectSingle',
                'items'               => [
                    ['', 0],
                ],
                'foreign_table'       => 'sys_file_storage',
                'foreign_table_where' => 'ORDER BY sys_file_storage.name',
                'size'                => 1,
                'minitems'            => 0,
                'maxitems'            => 1,
                'default'             => 0,
            ],
        ],
        'folder'  => [
            'exclude' => false,
            'label'   => 'LLL:EXT:pictures/Resources/Private/Language/locallang_db.xlf:tx_pictures_domain_model_album.folder',
            'config'  => [
                'type'          => 'select',
                'renderType'    => 'selectSingle',
                'items'         => [],
                'itemsProcFunc' => 'TYPO3\\CMS\\Core\\Resource\\Service\\UserFileMountService->renderTceformsSelectDropdown',
                'default'       => '',
            ],
        ],
        'poster' => [
            'exclude' => false,
            'label' => 'LLL:EXT:pictures/Resources/Private/Language/locallang_db.xlf:tx_pictures_domain_model_album.poster',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'poster',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
                    ],
                    'foreign_types' => [
                        '0' => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ]
                    ],
                    'maxitems' => 1
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
        'description' => [
            'exclude' => false,
            'label' => 'LLL:EXT:pictures/Resources/Private/Language/locallang_db.xlf:tx_pictures_domain_model_album.description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
                'fieldControl' => [
                    'fullScreenRichtext' => [
                        'disabled' => false,
                    ],
                ],
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],

        ],
    ],
];
