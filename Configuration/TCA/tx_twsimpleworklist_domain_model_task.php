<?php
return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:tw_simpleworklist/Resources/Private/Language/locallang_db.xlf:tx_twsimpleworklist_domain_model_task',
        'label' => 'title',
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
        'searchFields' => 'title,text,focus,task_state,last_task_state,task_energy,task_time,due_date,order_id_project,order_id_task_state,project,context,user_account,',
        'iconfile' => 'EXT:tw_simpleworklist/Resources/Public/Icons/tx_twsimpleworklist_domain_model_task.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, text, focus, task_state, last_task_state, task_energy, task_time, due_date, order_id_project, order_id_task_state, project, context, user_account',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, text, focus, task_state, last_task_state, task_energy, task_time, due_date, order_id_project, order_id_task_state, project, context, user_account, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
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
                'foreign_table' => 'tx_twsimpleworklist_domain_model_task',
                'foreign_table_where' => 'AND tx_twsimpleworklist_domain_model_task.pid=###CURRENT_PID### AND tx_twsimpleworklist_domain_model_task.sys_language_uid IN (-1,0)',
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
        
	
	    'title' => [
	        'exclude' => 1,
	        'label' => 'LLL:EXT:tw_simpleworklist/Resources/Private/Language/locallang_db.xlf:tx_twsimpleworklist_domain_model_task.title',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	        
	    ],
	    'text' => [
	        'exclude' => 1,
	        'label' => 'LLL:EXT:tw_simpleworklist/Resources/Private/Language/locallang_db.xlf:tx_twsimpleworklist_domain_model_task.text',
	        'config' => [
			    'type' => 'text',
			    'cols' => 40,
			    'rows' => 15,
			    'eval' => 'trim'
			]
	        
	    ],
	    'focus' => [
	        'exclude' => 1,
	        'label' => 'LLL:EXT:tw_simpleworklist/Resources/Private/Language/locallang_db.xlf:tx_twsimpleworklist_domain_model_task.focus',
	        'config' => [
			    'type' => 'check',
			    'default' => 0
			]
	        
	    ],
	    'task_state' => [
	        'exclude' => 1,
	        'label' => 'LLL:EXT:tw_simpleworklist/Resources/Private/Language/locallang_db.xlf:tx_twsimpleworklist_domain_model_task.task_state',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'items' => [
			        ['-- Label --', 0],
			    ],
			    'size' => 1,
			    'maxitems' => 1,
			    'eval' => ''
			],
	        
	    ],
	    'last_task_state' => [
	        'exclude' => 1,
	        'label' => 'LLL:EXT:tw_simpleworklist/Resources/Private/Language/locallang_db.xlf:tx_twsimpleworklist_domain_model_task.last_task_state',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'items' => [
			        ['-- Label --', 0],
			    ],
			    'size' => 1,
			    'maxitems' => 1,
			    'eval' => ''
			],
	        
	    ],
	    'task_energy' => [
	        'exclude' => 1,
	        'label' => 'LLL:EXT:tw_simpleworklist/Resources/Private/Language/locallang_db.xlf:tx_twsimpleworklist_domain_model_task.task_energy',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'items' => [
			        ['-- Label --', 0],
			    ],
			    'size' => 1,
			    'maxitems' => 1,
			    'eval' => ''
			],
	        
	    ],
	    'task_time' => [
	        'exclude' => 1,
	        'label' => 'LLL:EXT:tw_simpleworklist/Resources/Private/Language/locallang_db.xlf:tx_twsimpleworklist_domain_model_task.task_time',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'items' => [
			        ['-- Label --', 0],
			    ],
			    'size' => 1,
			    'maxitems' => 1,
			    'eval' => ''
			],
	        
	    ],
	    'due_date' => [
	        'exclude' => 1,
	        'label' => 'LLL:EXT:tw_simpleworklist/Resources/Private/Language/locallang_db.xlf:tx_twsimpleworklist_domain_model_task.due_date',
	        'config' => [
			    'dbType' => 'date',
			    'type' => 'input',
			    'size' => 7,
			    'eval' => 'date',
			    'checkbox' => 0,
			    'default' => '0000-00-00'
			],
	        
	    ],
	    'order_id_project' => [
	        'exclude' => 1,
	        'label' => 'LLL:EXT:tw_simpleworklist/Resources/Private/Language/locallang_db.xlf:tx_twsimpleworklist_domain_model_task.order_id_project',
	        'config' => [
			    'type' => 'input',
			    'size' => 4,
			    'eval' => 'int'
			]
	        
	    ],
	    'order_id_task_state' => [
	        'exclude' => 1,
	        'label' => 'LLL:EXT:tw_simpleworklist/Resources/Private/Language/locallang_db.xlf:tx_twsimpleworklist_domain_model_task.order_id_task_state',
	        'config' => [
			    'type' => 'input',
			    'size' => 4,
			    'eval' => 'int'
			]
	        
	    ],
	    'project' => [
	        'exclude' => 1,
	        'label' => 'LLL:EXT:tw_simpleworklist/Resources/Private/Language/locallang_db.xlf:tx_twsimpleworklist_domain_model_task.project',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'foreign_table' => 'tx_twsimpleworklist_domain_model_project',
			    'minitems' => 0,
			    'maxitems' => 1,
			],
	        
	    ],
	    'context' => [
	        'exclude' => 1,
	        'label' => 'LLL:EXT:tw_simpleworklist/Resources/Private/Language/locallang_db.xlf:tx_twsimpleworklist_domain_model_task.context',
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
	        'label' => 'LLL:EXT:tw_simpleworklist/Resources/Private/Language/locallang_db.xlf:tx_twsimpleworklist_domain_model_task.user_account',
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
