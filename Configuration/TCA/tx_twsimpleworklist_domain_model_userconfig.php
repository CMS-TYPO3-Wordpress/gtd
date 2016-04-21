<?php
return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:tw_simpleworklist/Resources/Private/Language/locallang_db.xlf:tx_twsimpleworklist_domain_model_userconfig',
        'label' => 'default_context',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => 1,
		'versioningWS' => 2,
        'versioning_followPages' => true,

        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
        'enablecolumns' => [
			'disabled' => 'hidden',
			'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'default_context,user_account,',
        'iconfile' => 'EXT:tw_simpleworklist/Resources/Public/Icons/tx_twsimpleworklist_domain_model_userconfig.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, default_context, user_account',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, default_context, user_account, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages'
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_twsimpleworklist_domain_model_userconfig',
                'foreign_table_where' => 'AND tx_twsimpleworklist_domain_model_userconfig.pid=###CURRENT_PID### AND tx_twsimpleworklist_domain_model_userconfig.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],

	    'default_context' => [
	        'exclude' => 1,
	        'label' => 'LLL:EXT:tw_simpleworklist/Resources/Private/Language/locallang_db.xlf:tx_twsimpleworklist_domain_model_userconfig.default_context',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'foreign_table' => 'tx_twsimpleworklist_domain_model_context',
			    'minitems' => 0,
			    'maxitems' => 1,
			],
	        
	    ],
	    'user_account' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:tw_simpleworklist/Resources/Private/Language/locallang_db.xlf:tx_twsimpleworklist_domain_model_usermessage.receiver',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'tx_twsimpleworklist_domain_model_useraccount',
				'minitems' => 0,
				'maxitems' => 1,
			],
	        
	    ],
        
    ],
];
## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder