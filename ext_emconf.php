<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "gtd"
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'Getting Things Done',
    'description' => 'Frontend User can use this Extension as Todo List for Timemanagement or Projectmanangement with the Getting Things Done Scheme introduced by David Allen.',
    'category' => 'plugin',
    'author' => 'Thomas Woehlke',
    'author_email' => 'thomas@woehlke.org',
    'author_company' => 'faktura gGmbH',
    'shy' => '',
    'priority' => '',
    'module' => '',
    'state' => 'beta',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => 0,
    'lockType' => '',
    'version' => '0.9.0',
    'constraints' => [
        'depends' => [
            'felogin' => '7.6.0-7.99.99',
            'extbase' => '7.6.0-7.99.99',
            'fluid' => '7.6.0-7.99.99',
            'typo3' => '7.6.0-7.99.99',
            'php' => '5.6.0-7.99.99',
        ],
        'conflicts' => [],
        'suggests' => [
            't3sbootstrap' => '3.1.0-3.1.99',
        ],
    ],
];
