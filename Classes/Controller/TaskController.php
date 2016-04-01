<?php
namespace ThomasWoehlke\TwSimpleworklist\Controller;

/***
 *
 * This file is part of the "SimpleWorklist" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2016 Thomas Woehlke <woehlke@faktura-berlin.de>, faktura gGmbH
 *
 ***/

/**
 * TaskController
 */
class TaskController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * taskRepository
     * 
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Repository\TaskRepository
     * @inject
     */
    protected $taskRepository = null;
    
    /**
     * action show
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task
     * @return void
     */
    public function showAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task)
    {
        $this->view->assign('task', $task);
    }
    
    /**
     * action edit
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task
     * @ignorevalidation $task
     * @return void
     */
    public function editAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task)
    {
        $this->view->assign('task', $task);
    }
    
    /**
     * action update
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task
     * @return void
     */
    public function updateAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->taskRepository->update($task);
        $this->redirect('list');
    }
    
    /**
     * action inbox
     * 
     * @return void
     */
    public function inboxAction()
    {
        
    }
    
    /**
     * action today
     * 
     * @return void
     */
    public function todayAction()
    {
        
    }
    
    /**
     * action next
     * 
     * @return void
     */
    public function nextAction()
    {
        
    }
    
    /**
     * action waiting
     * 
     * @return void
     */
    public function waitingAction()
    {
        
    }
    
    /**
     * action scheduled
     * 
     * @return void
     */
    public function scheduledAction()
    {
        
    }
    
    /**
     * action someday
     * 
     * @return void
     */
    public function somedayAction()
    {
        
    }
    
    /**
     * action completed
     * 
     * @return void
     */
    public function completedAction()
    {
        
    }
    
    /**
     * action trash
     * 
     * @return void
     */
    public function trashAction()
    {
        
    }
    
    /**
     * action emptyTrash
     * 
     * @return void
     */
    public function emptyTrashAction()
    {
        
    }
    
    /**
     * action transformTaskIntoProject
     * 
     * @return void
     */
    public function transformTaskIntoProjectAction()
    {
        
    }
    
    /**
     * action completeTask
     * 
     * @return void
     */
    public function completeTaskAction()
    {
        
    }
    
    /**
     * action undoneTask
     * 
     * @return void
     */
    public function undoneTaskAction()
    {
        
    }
    
    /**
     * action setFocus
     * 
     * @return void
     */
    public function setFocusAction()
    {
        
    }
    
    /**
     * action unsetFocus
     * 
     * @return void
     */
    public function unsetFocusAction()
    {
        
    }
    
    /**
     * action getAllTasksForUser
     * 
     * @return void
     */
    public function getAllTasksForUserAction()
    {
        
    }
    
    /**
     * action changeTaskOrderId
     * 
     * @return void
     */
    public function changeTaskOrderIdAction()
    {
        
    }
    
    /**
     * action changeTaskOrderIdByProject
     * 
     * @return void
     */
    public function changeTaskOrderIdByProjectAction()
    {
        
    }
    
    /**
     * action addNewTaskToProject
     * 
     * @return void
     */
    public function addNewTaskToProjectAction()
    {
        
    }
    
    /**
     * action addNewTaskToInbox
     * 
     * @return void
     */
    public function addNewTaskToInboxAction()
    {
        
    }
}
