<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'ThomasWoehlke.' . $extKey,
            'Frontendsimpleworklist',
            [
                'ProjectController' => 'addNewRootProject, addNewChildProject, ',
                'TaskController' => 'inbox, today, next, waiting, scheduled, someday, completed, trash, emptyTrash, transformTaskIntoProject, completeTask, undoneTask, setFocus, unsetFocus, getAllTasksForUser, changeTaskOrderId, changeTaskOrderIdByProject, addNewTaskToProject, addNewTaskToInbox'
            ],
            // non-cacheable actions
            [
                'UserAccount' => 'update',
                'UserMessage' => '',
                'Context' => '',
                'Project' => 'update, delete, ',
                'Task' => 'update, '
            ]
        );

    },
    $_EXTKEY
);
