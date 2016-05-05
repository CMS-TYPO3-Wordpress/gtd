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
use ThomasWoehlke\TwSimpleworklist\Domain\Model\Project;

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
     * projectRepository
     *
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Repository\ProjectRepository
     * @inject
     */
    protected $projectRepository = null;

    /**
     * contextService
     *
     * @var \ThomasWoehlke\TwSimpleworklist\Service\ContextService
     * @inject
     */
    protected $contextService = null;

    protected $taskStates = array(
        'inbox' => 0, 'today' => 1, 'next' => 2, 'waiting' => 3, 'scheduled' => 4, 'someday' => 5, 'completed' => 6 , 'trash' => 7
    );

    /**
     * action show
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task
     * @return void
     */
    public function showAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task)
    {
        $this->view->assign('task', $task);
        $this->getTaskEnergyAndTaskTime();
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$this->contextService->getCurrentContext());
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($this->contextService->getCurrentContext()));
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
        $this->getTaskEnergyAndTaskTime();
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$this->contextService->getCurrentContext());
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($this->contextService->getCurrentContext()));
    }

    public function initializeEditAction()
    {
        $this->arguments['task']
            ->getPropertyMappingConfiguration()
            ->forProperty('dueDate')
            ->setTypeConverterOption('TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter',
                \TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 'Y-m-d');
    }

    /**
     * action update
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task
     * @return void
     */
    public function updateAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task)
    {
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $persistentTask = $this->taskRepository->findByUid($task->getUid());
        $persistentTask->setTitle($task->getTitle());
        $persistentTask->setText($task->getText());
        $persistentTask->setTaskEnergy($task->getTaskEnergy());
        $persistentTask->setTaskTime($task->getTaskTime());
        $persistentTask->setDueDate($task->getDueDate());
        if($task->getDueDate() != NULL){
            $persistentTask->changeTaskState($this->taskStates['scheduled']);
            $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,$this->taskStates['scheduled']);
            $persistentTask->setOrderIdTaskState($maxTaskStateOrderId);
        } else {
            if($persistentTask->getTaskState() == $this->taskStates['scheduled']){
                $persistentTask->changeTaskState($this->taskStates['inbox']);
            }
            $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,$persistentTask->getTaskState());
            $persistentTask->setOrderIdTaskState($maxTaskStateOrderId);
        }
        $this->taskRepository->update($persistentTask);
        $this->getRedirectFromTask($persistentTask);
    }

    private function getRedirectFromTask(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task){
        switch($task->getTaskState()){
            case $this->taskStates['inbox']:
                $this->redirect('inbox');
                break;
            case $this->taskStates['today']:
                $this->redirect('today');
                break;
            case $this->taskStates['next']:
                $this->redirect('next');
                break;
            case $this->taskStates['waiting']:
                $this->redirect('waiting');
                break;
            case $this->taskStates['scheduled']:
                $this->redirect('scheduled');
                break;
            case $this->taskStates['someday']:
                $this->redirect('someday');
                break;
            case $this->taskStates['completed']:
                $this->redirect('completed');
                break;
            case $this->taskStates['trash']:
                $this->redirect('trash');
                break;
            default:
                $this->redirect('list');
                break;
        }
    }

    public function initializeUpdateAction()
    {
        $this->arguments['task']
            ->getPropertyMappingConfiguration()
            ->forProperty('dueDate')
            ->setTypeConverterOption('TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter',
                \TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 'Y-m-d');
    }
    
    /**
     * action inbox
     * 
     * @return void
     */
    public function inboxAction()
    {
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $tasks = $this->taskRepository->findByUserAccountAndTaskState($userObject,$currentContext,$this->taskStates['inbox']);
        $this->view->assign('tasks', $tasks);
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$this->contextService->getCurrentContext());
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($currentContext));
    }
    
    /**
     * action today
     * 
     * @return void
     */
    public function todayAction()
    {
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $tasks = $this->taskRepository->findByUserAccountAndTaskState($userObject,$currentContext,$this->taskStates['today']);
        $this->view->assign('tasks', $tasks);
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$this->contextService->getCurrentContext());
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($currentContext));
    }
    
    /**
     * action next
     * 
     * @return void
     */
    public function nextAction()
    {
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $tasks = $this->taskRepository->findByUserAccountAndTaskState($userObject,$currentContext,$this->taskStates['next']);
        $this->view->assign('tasks', $tasks);
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$this->contextService->getCurrentContext());
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($currentContext));
    }
    
    /**
     * action waiting
     * 
     * @return void
     */
    public function waitingAction()
    {
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $tasks = $this->taskRepository->findByUserAccountAndTaskState($userObject,$currentContext,$this->taskStates['waiting']);
        $this->view->assign('tasks', $tasks);
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$this->contextService->getCurrentContext());
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($currentContext));
    }
    
    /**
     * action scheduled
     * 
     * @return void
     */
    public function scheduledAction()
    {
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $tasks = $this->taskRepository->findByUserAccountAndTaskState($userObject,$currentContext,$this->taskStates['scheduled']);
        $this->view->assign('tasks', $tasks);
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$this->contextService->getCurrentContext());
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($currentContext));
    }
    
    /**
     * action someday
     * 
     * @return void
     */
    public function somedayAction()
    {
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $tasks = $this->taskRepository->findByUserAccountAndTaskState($userObject,$currentContext,$this->taskStates['someday']);
        $this->view->assign('tasks', $tasks);
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$this->contextService->getCurrentContext());
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($currentContext));
    }
    
    /**
     * action completed
     * 
     * @return void
     */
    public function completedAction()
    {
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $tasks = $this->taskRepository->findByUserAccountAndTaskState($userObject,$currentContext,$this->taskStates['completed']);
        $this->view->assign('tasks', $tasks);
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$this->contextService->getCurrentContext());
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($currentContext));
    }
    
    /**
     * action trash
     * 
     * @return void
     */
    public function trashAction()
    {
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $tasks = $this->taskRepository->findByUserAccountAndTaskState($userObject,$currentContext,$this->taskStates['trash']);
        $this->view->assign('tasks', $tasks);
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$this->contextService->getCurrentContext());
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($currentContext));
    }

    /**
     * action focus
     *
     * @return void
     */
    public function focusAction()
    {
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $tasks = $this->taskRepository->findByUserAccountAndHasFocus($userObject,$currentContext);
        $this->view->assign('tasks', $tasks);
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$this->contextService->getCurrentContext());
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($currentContext));
    }
    
    /**
     * action emptyTrash
     * 
     * @return void
     */
    public function emptyTrashAction()
    {
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $tasks = $this->taskRepository->findByUserAccountAndTaskState($userObject,$currentContext,$this->taskStates['trash']);
        foreach($tasks as $task){
            $this->taskRepository->remove($task);
        }
        $this->redirect('trash');
    }
    
    /**
     * action transformTaskIntoProject
     *
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task
     * @return void
     */
    public function transformTaskIntoProjectAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task)
    {
        $parentProject = $task->getProject();
        $newProject = new Project();
        $newProject->setContext($task->getContext());
        $newProject->setUserAccount($task->getUserAccount());
        $newProject->setParent($parentProject);
        $newProject->setName($task->getTitle());
        $newProject->setDescription($task->getText());
        if($parentProject != null){
            $parentProject->addChild($newProject);
            $this->projectRepository->update($parentProject);
        }
        $this->projectRepository->add($newProject);
        $this->taskRepository->remove($task);
        $args = array("project" => $parentProject);
        $this->redirect('show',"Project",null,$args);
    }
    
    /**
     * action completeTask
     *
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task
     * @return void
     */
    public function completeTaskAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task)
    {
        $task->changeTaskState($this->taskStates['completed']);
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,$this->taskStates['completed']);
        $task->setOrderIdTaskState($maxTaskStateOrderId);
        $this->taskRepository->update($task);
        $this->getRedirectFromTask($task);
    }
    
    /**
     * action undoneTask
     *
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task
     * @return void
     */
    public function undoneTaskAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task)
    {
        $task->setToLastTaskState();
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,$task->getTaskState());
        $task->setOrderIdTaskState($maxTaskStateOrderId);
        $this->taskRepository->update($task);
        $this->getRedirectFromTask($task);
    }
    
    /**
     * action setFocus
     *
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task
     * @return void
     */
    public function setFocusAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task)
    {
        $task->setFocus(true);
        $this->taskRepository->update($task);
        $this->getRedirectFromTask($task);
    }
    
    /**
     * action unsetFocus
     *
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task
     * @return void
     */
    public function unsetFocusAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task)
    {
        $task->setFocus(false);
        $this->taskRepository->update($task);
        $this->getRedirectFromTask($task);
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
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$this->contextService->getCurrentContext());
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($this->contextService->getCurrentContext()));
    }

    private function getTaskEnergyAndTaskTime(){
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
     * action new
     * 
     * @return void
     */
    public function newAction()
    {
        $this->getTaskEnergyAndTaskTime();
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$this->contextService->getCurrentContext());
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($this->contextService->getCurrentContext()));
    }

    /**
     * action create
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $newTask
     * @return void
     */
    public function createAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $newTask)
    {
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $newTask->setContext($currentContext);
        $newTask->setUserAccount($userObject);
        $newTask->setTaskState($this->taskStates['inbox']);
        $projectOrderId = $this->taskRepository->getMaxProjectOrderId(null);
        $newTask->setOrderIdProject($projectOrderId);
        $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,$this->taskStates['inbox']);
        $newTask->setOrderIdTaskState($maxTaskStateOrderId);
        if($newTask->getDueDate() != NULL){
            $newTask->setTaskState($this->taskStates['scheduled']);
            $this->taskRepository->add($newTask);
            $this->redirect('scheduled');
        } else {
            $newTask->setTaskState($this->taskStates['inbox']);
            $this->taskRepository->add($newTask);
            $this->redirect('inbox');
        }
    }

    public function initializeCreateAction()
    {
        $this->arguments['newTask']
            ->getPropertyMappingConfiguration()
            ->forProperty('dueDate')
            ->setTypeConverterOption('TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter', 
                \TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 'Y-m-d');
    }
    
    /**
     * action moveToInbox
     *
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task
     * @return void
     */
    public function moveToInboxAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task){
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,$this->taskStates['inbox']);
        $task->setOrderIdTaskState($maxTaskStateOrderId);
        $task->changeTaskState($this->taskStates['inbox']);
        $this->taskRepository->update($task);
        $this->redirect('inbox');
    }

    /**
     * action moveToToday
     *
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task
     * @return void
     */
    public function moveToTodayAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task){
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,$this->taskStates['today']);
        $task->setOrderIdTaskState($maxTaskStateOrderId);
        $task->changeTaskState($this->taskStates['today']);
        $this->taskRepository->update($task);
        $this->redirect('today');
    }

    /**
     * action moveToNext
     *
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task
     * @return void
     */
    public function moveToNextAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task){
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,$this->taskStates['next']);
        $task->setOrderIdTaskState($maxTaskStateOrderId);
        $task->changeTaskState($this->taskStates['next']);
        $this->taskRepository->update($task);
        $this->redirect('next');
    }

    /**
     * action moveToWaiting
     *
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task
     * @return void
     */
    public function moveToWaitingAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task){
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,$this->taskStates['waiting']);
        $task->setOrderIdTaskState($maxTaskStateOrderId);
        $task->changeTaskState($this->taskStates['waiting']);
        $this->taskRepository->update($task);
        $this->redirect('waiting');
    }

    /**
     * action moveToSomeday
     *
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task
     * @return void
     */
    public function moveToSomedayAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task){
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,$this->taskStates['someday']);
        $task->setOrderIdTaskState($maxTaskStateOrderId);
        $task->changeTaskState($this->taskStates['someday']);
        $this->taskRepository->update($task);
        $this->redirect('someday');
    }

    /**
     * action moveToCompleted
     *
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task
     * @return void
     */
    public function moveToCompletedAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task){
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,$this->taskStates['completed']);
        $task->setOrderIdTaskState($maxTaskStateOrderId);
        $task->changeTaskState($this->taskStates['completed']);
        $this->taskRepository->update($task);
        $this->redirect('completed');
    }

    /**
     * action moveToTrash
     *
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task
     * @return void
     */
    public function moveToTrashAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $task){
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,$this->taskStates['trash']);
        $task->setOrderIdTaskState($maxTaskStateOrderId);
        $task->changeTaskState($this->taskStates['trash']);
        $this->taskRepository->update($task);
        $this->redirect('trash');
    }

    /**
     * action moveAllCompletedToTrash
     *
     * @return void
     */
    public function moveAllCompletedToTrashAction(){
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $tasks = $this->taskRepository->findByUserAccountAndTaskState($userObject,$currentContext, $this->taskStates['completed']);
        $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,$this->taskStates['trash']);
        foreach($tasks as $task){
            $task->changeTaskState($this->taskStates['trash']);
            $task->setOrderIdTaskState($maxTaskStateOrderId);
            $this->taskRepository->update($task);
            $maxTaskStateOrderId++;
        }
        $this->redirect('trash');
    }

    /**
     * action moveTaskOrder
     *
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $srcTask
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $targetTask
     * @return void
     */
    public function moveTaskOrderAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $srcTask,
                                        \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $targetTask){
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $destinationTaskOrderId = $targetTask->getOrderIdTaskState();
        if($srcTask->getOrderIdTaskState()<$targetTask->getOrderIdTaskState()){
            $tasks = $this->taskRepository->getTasksToReorderByOrderIdTaskState($userObject, $currentContext, $srcTask, $targetTask, $srcTask->getTaskState());
            foreach ($tasks as $task){
                $task->setOrderIdTaskState($task->getOrderIdTaskState()-1);
                $this->taskRepository->update($task);
            }
            $targetTask->setOrderIdTaskState($targetTask->getOrderIdTaskState()-1);
            $this->taskRepository->update($targetTask);
            $srcTask->setOrderIdTaskState($destinationTaskOrderId);
            $this->taskRepository->update($srcTask);
        } else {
            $tasks = $this->taskRepository->getTasksToReorderByOrderIdTaskState($userObject, $currentContext, $targetTask, $srcTask, $srcTask->getTaskState());
            foreach ($tasks as $task){
                $task->setOrderIdTaskState($task->getOrderIdTaskState()+1);
                $this->taskRepository->update($task);
            }
            $srcTask->setOrderIdTaskState($destinationTaskOrderId+1);
            $this->taskRepository->update($srcTask);
        }
        $this->getRedirectFromTask($srcTask);
    }

    /**
     * action moveTaskOrderInsideProject
     *
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $srcTask
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $targetTask
     * @return void
     */
    public function moveTaskOrderInsideProjectAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $srcTask,
                                                     \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $targetTask){
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $project = $srcTask->getProject();
        $destinationProjectOrderId = $targetTask->getOrderIdProject();
        if($srcTask->getOrderIdProject()<$targetTask->getOrderIdProject()){
            $tasks = $this->taskRepository->getTasksToReorderByOrderIdProject($userObject, $currentContext, $srcTask, $targetTask, $project);
            foreach ($tasks as $task){
                $task->setOrderIdProject($task->getOrderIdProject()-1);
                $this->taskRepository->update($task);
            }
            $targetTask->setOrderIdProject($targetTask->getOrderIdProject()-1);
            $this->taskRepository->update($targetTask);
            $srcTask->setOrderIdProject($destinationProjectOrderId);
            $this->taskRepository->update($srcTask);
        } else {
            $tasks = $this->taskRepository->getTasksToReorderByOrderIdProject($userObject, $currentContext, $targetTask, $srcTask, $project);
            foreach ($tasks as $task){
                $task->setOrderIdProject($task->getOrderIdProject()+1);
                $this->taskRepository->update($task);
            }
            $srcTask->setOrderIdProject($destinationProjectOrderId+1);
            $this->taskRepository->update($srcTask);
        }
        $args = array('project'=>$project);
        $this->redirect('show','Project',null,$args);
    }
}
