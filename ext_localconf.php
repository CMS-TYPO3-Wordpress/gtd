<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'ThomasWoehlke.' . $extKey,
            'Frontendgtd',
            [
                'UserAccount' => 'list',
                'UserMessage' => 'list, create',
                'UserConfig' => 'show, update',
                'Context' => 'switchContext, show, new, create, edit, update, delete',
                'Project' => 'list, show, new, create, edit, update, delete, moveTask, moveProject, createTestData',
                'Task' => 'list, show, edit, update, new, create, focus, inbox, today, next, waiting, scheduled, someday, completed, trash, emptyTrash, moveTaskOrderInsideProject, transformTaskIntoProject, completeTask, undoneTask, setFocus, unsetFocus, moveToInbox, moveToToday, moveToNext, moveToWaiting, moveToSomeday, moveToCompleted, moveToTrash, moveAllCompletedToTrash, moveTaskOrder, uploadFiles'
            ],
            // non-cacheable actions
            [
                'UserAccount' => 'list',
                'UserMessage' => 'list, create',
                'UserConfig' => 'show, update',
                'Context' => 'switchContext, show, new, create, edit, update, delete',
                'Project' => 'list, show, new, create, edit, update, delete, moveTask, moveProject, createTestData',
                'Task' => 'list, show, edit, update, new, create, focus, inbox, today, next, waiting, scheduled, someday, completed, trash, emptyTrash, moveTaskOrderInsideProject, transformTaskIntoProject, completeTask, undoneTask, setFocus, unsetFocus, moveToInbox, moveToToday, moveToNext, moveToWaiting, moveToSomeday, moveToCompleted, moveToTrash, moveAllCompletedToTrash, moveTaskOrder, uploadFiles'
            ]
        );

    },
    $_EXTKEY
);


$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']
['ThomasWoehlke\\Gtd\\Command\\TaskSchedulingController'] = array(
    'extension' => $_EXTKEY,
    'title' => 'Move scheduled Tasks to today, if dueDate is current day',
    'description' => 'Move Tasks from TasksList Scheduled to TaskList Today, if their dueDate is the current day',
    'additionalFields' => ''
);
