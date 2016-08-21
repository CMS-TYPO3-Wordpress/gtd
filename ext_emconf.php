<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "gtd"
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'Todo-List in the Style of Getting Things Done',
    'description' => 'Frontend Users can use this Extension as Todo List for Timemanagement or Projectmanangement with the Getting Things Done Scheme introduced by David Allen.',
    'category' => 'plugin',
    'author' => 'Thomas Woehlke',
    'author_email' => 'thomas@woehlke.org',
    'author_company' => 'faktura gGmbH',
    'shy' => '',
    'priority' => '',
    'module' => '',
    'state' => 'beta',
    'internal' => '',
    'uploadfolder' => '1',
    'createDirs' => 'uploads/tx_gtd',
    'clearCacheOnLoad' => 1,
    'lockType' => '',
    'version' => '0.10.1',
    'constraints' => [
        'depends' => [
            'felogin' => '6.2.0-7.99.99',
            'scheduler' => '6.2.0-7.99.99',
            'extbase' => '6.2.0-7.99.99',
            'fluid' => '6.2.0-7.99.99',
            'typo3' => '6.2.0-7.99.99',
            'php' => '5.6.0-7.99.99',
        ],
        'conflicts' => [],
        'suggests' => [
            't3sbootstrap' => '3.1.0-3.1.99',
        ],
    ],
];
