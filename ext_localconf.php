<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'ThomasWoehlke.' . $extKey,
            'Frontendgtd',
            [
                'Task' => 'inbox, edit, update, new, create, focus, today, next, waiting, scheduled, someday, completed, trash, emptyTrash, moveTaskOrderInsideProject, transformTaskIntoProject, completeTask, undoneTask, setFocus, unsetFocus, moveToInbox, moveToToday, moveToNext, moveToWaiting, moveToSomeday, moveToCompleted, moveToTrash, moveAllCompletedToTrash, moveTaskOrder, uploadFiles',
                'UserAccount' => 'list',
                'UserMessage' => 'list, create',
                'UserConfig' => 'show, update',
                'Context' => 'switchContext, new, create, edit, update, delete',
                'Project' => 'list, show, new, create, edit, update, delete, moveTask, moveProject, createTestData'
            ],
            // non-cacheable actions
            [
                'Task' => 'inbox, edit, update, new, create, focus, today, next, waiting, scheduled, someday, completed, trash, emptyTrash, moveTaskOrderInsideProject, transformTaskIntoProject, completeTask, undoneTask, setFocus, unsetFocus, moveToInbox, moveToToday, moveToNext, moveToWaiting, moveToSomeday, moveToCompleted, moveToTrash, moveAllCompletedToTrash, moveTaskOrder, uploadFiles',
                'UserAccount' => 'list',
                'UserMessage' => 'list, create',
                'UserConfig' => 'show, update',
                'Context' => 'switchContext, new, create, edit, update, delete',
                'Project' => 'list, show, new, create, edit, update, delete, moveTask, moveProject, createTestData'
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

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']
['ThomasWoehlke\\Gtd\\Command\\RemoveUnusedFilesController'] = array(
    'extension' => $_EXTKEY,
    'title' => 'remove unused Files from deleted gtd-Tasks',
    'description' => 'Files may be uploaded and added to task, after tasks are deleted these files may be unused',
    'additionalFields' => ''
);
