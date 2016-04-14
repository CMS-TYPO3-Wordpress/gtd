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
                'Project' => 'list, show, edit, addNewRootProject, addNewChildProject, moveProject, getAllProjects, getRootProjects',
                'Task' => 'list, show, edit, update, new, create, focus, inbox, today, next, waiting, scheduled, someday, completed, trash, emptyTrash, transformTaskIntoProject, completeTask, undoneTask, setFocus, unsetFocus, getAllTasksForUser, changeTaskOrderId, changeTaskOrderIdByProject, addNewTaskToProject, addNewTaskToInbox, moveToInbox, moveToToday, moveToNext, moveToWaiting, moveToSomeday, moveToCompleted, moveToTrash, moveAllCompletedToTrash'
            ],
            // non-cacheable actions
            [
                'UserAccount' => 'list show, edit, update, delete',
                'Project' => 'list, show, edit, addNewRootProject, addNewChildProject, moveProject, getAllProjects, getRootProjects',
                'Task' => 'list, show, edit, update, new, create, focus, inbox, today, next, waiting, scheduled, someday, completed, trash, emptyTrash, transformTaskIntoProject, completeTask, undoneTask, setFocus, unsetFocus, getAllTasksForUser, changeTaskOrderId, changeTaskOrderIdByProject, addNewTaskToProject, addNewTaskToInbox, moveToInbox, moveToToday, moveToNext, moveToWaiting, moveToSomeday, moveToCompleted, moveToTrash, moveAllCompletedToTrash'
            ]
        );

    },
    $_EXTKEY
);
