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
     * userAccountRepository
     *
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Repository\UserAccountRepository
     * @inject
     */
    protected $userAccountRepository = null;

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
        $tasks = $this->taskRepository->findAll();
        $this->view->assign('tasks', $tasks);
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
    
    /**
     * action list
     * 
     * @return void
     */
    public function listAction()
    {
        $tasks = $this->taskRepository->findAll();
        $this->view->assign('tasks', $tasks);
    }
    
    /**
     * action new
     * 
     * @return void
     */
    public function newAction()
    {
        $taskEnergy = array(
            0 => 'none',
            1 => 'low',
            2 => 'mid',
            3 => 'high'
        );
        $taskTime = array(
            0 => 'none',
            1 => '5 min',
            2 => '10 min',
            3 => '15 min',
            4 => '30 min',
            5 => '45 min',
            6 => '1 hours',
            7 => '2 hours',
            8 => '3 hours',
            9 => '4 hours',
            10 => '6 hours',
            11 => '8 hours',
            12 => 'more'
        );
        $this->view->assign('taskEnergy',$taskEnergy);
        $this->view->assign('taskTime',$taskTime);
    }

    /**
     * action create
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $newTask
     * @return void
     */
    public function createAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $newTask)
    {
        //$this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);

        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $newTask->setUserAccount($userObject);
        $newTask->setTaskState(1);
        $newTask->setOrderIdProject(1);
        $newTask->setOrderIdTaskState(1);
        $this->taskRepository->add($newTask);
        $this->redirect('inbox');
    }
    
    /**
     * action delete
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task
     * @return void
     */
    public function deleteAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->taskRepository->remove($task);
        $this->redirect('list');
    }
}
