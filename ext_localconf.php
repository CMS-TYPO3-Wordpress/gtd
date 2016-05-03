<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'ThomasWoehlke.' . $extKey,
            'Frontendsimpleworklist',
            [
                'UserAccount' => 'list, show, edit, update, delete',
                'UserMessage' => 'list, create',
                'Context' => 'switchContext',
                'Project' => 'list, show, new, create, edit, update, delete, moveTask, addNewRootProject, addNewChildProject, moveProject, getAllProjects, getRootProjects, createTestData',
                'Task' => 'list, show, edit, update, new, create, focus, inbox, today, next, waiting, scheduled, someday, completed, trash, emptyTrash, transformTaskIntoProject, completeTask, undoneTask, setFocus, unsetFocus, getAllTasksForUser, changeTaskOrderIdByProject, addNewTaskToProject, moveToInbox, moveToToday, moveToNext, moveToWaiting, moveToSomeday, moveToCompleted, moveToTrash, moveAllCompletedToTrash, moveTaskOrder'
            ],
            // non-cacheable actions
            [
                'UserAccount' => 'list show, edit, update, delete',
                'UserMessage' => 'list, create',
                'Context' => 'switchContext',
                'Project' => 'list, show, new, create, edit, update, delete, moveTask, addNewRootProject, addNewChildProject, moveProject, getAllProjects, getRootProjects, createTestData',
                'Task' => 'list, show, edit, update, new, create, focus, inbox, today, next, waiting, scheduled, someday, completed, trash, emptyTrash, transformTaskIntoProject, completeTask, undoneTask, setFocus, unsetFocus, getAllTasksForUser, changeTaskOrderIdByProject, addNewTaskToProject, moveToInbox, moveToToday, moveToNext, moveToWaiting, moveToSomeday, moveToCompleted, moveToTrash, moveAllCompletedToTrash, moveTaskOrder'
            ]
        );

    },
    $_EXTKEY
);
