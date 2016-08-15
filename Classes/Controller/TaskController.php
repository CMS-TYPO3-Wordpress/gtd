<?php
namespace ThomasWoehlke\Gtd\Controller;

/***
 *
 * This file is part of the "Getting Things Done" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2016 Thomas Woehlke <thomas@woehlke.org>, faktura gGmbH
 *
 ***/
use ThomasWoehlke\Gtd\Domain\Model\Project;
use \ThomasWoehlke\Gtd\Domain\Model\Task;

/**
 * Class TaskController
 *
 * @package ThomasWoehlke\Gtd\Controller
 */
class TaskController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * taskRepository
     *
     * @var \ThomasWoehlke\Gtd\Domain\Repository\TaskRepository
     * @inject
     */
    protected $taskRepository = null;

    /**
     * userAccountRepository
     *
     * @var \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository
     * @inject
     */
    protected $userAccountRepository = null;

    /**
     * projectRepository
     *
     * @var \ThomasWoehlke\Gtd\Domain\Repository\ProjectRepository
     * @inject
     */
    protected $projectRepository = null;

    /**
     * contextService
     *
     * @var \ThomasWoehlke\Gtd\Service\ContextService
     * @inject
     */
    protected $contextService = null;

    private $extName = 'gtd';

    /**
     * action edit
     *
     * @param \ThomasWoehlke\Gtd\Domain\Model\Task $task
     * @ignorevalidation $task
     * @return void
     */
    public function editAction(\ThomasWoehlke\Gtd\Domain\Model\Task $task)
    {
        $ctx = $this->contextService->getCurrentContext();
        $this->view->assign('task', $task);
        $this->getTaskEnergyAndTaskTime();
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$ctx);
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($ctx));
        $this->view->assign('langKey',$this->getLanguageId());
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
     * @param \ThomasWoehlke\Gtd\Domain\Model\Task $task
     * @return void
     */
    public function updateAction(\ThomasWoehlke\Gtd\Domain\Model\Task $task)
    {
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $persistentTask = $this->taskRepository->findByUid($task->getUid());
        $persistentTask->setTitle($task->getTitle());
        $persistentTask->setText($task->getText());
        $persistentTask->setTaskEnergy($task->getTaskEnergy());
        $persistentTask->setTaskTime($task->getTaskTime());
        $persistentTask->setDueDate($task->getDueDate());
        if($task->getDueDate() != NULL){
            $persistentTask->changeTaskState(Task::$TASK_STATES['scheduled']);
            $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,Task::$TASK_STATES['scheduled']);
            $persistentTask->setOrderIdTaskState($maxTaskStateOrderId);
        } else {
            if($persistentTask->getTaskState() == Task::$TASK_STATES['scheduled']){
                $persistentTask->changeTaskState(Task::$TASK_STATES['inbox']);
            }
            $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,$persistentTask->getTaskState());
            $persistentTask->setOrderIdTaskState($maxTaskStateOrderId);
        }
        if($this->request !== null) {
            if ($this->request->hasArgument('file')) {
                $persistentTask->setFiles(str_replace('uploads/tx_gtd/', '', $this->request->getArgument('file')));
            }
        }
        $this->taskRepository->update($persistentTask);
//        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($persistentTask);
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.task.updated', $this->extName, null);
        $msg .= ' ( '.htmlspecialchars($task->getTitle()).' )';
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->getRedirectFromTask($persistentTask);
    }



    /**
     * @param \ThomasWoehlke\Gtd\Domain\Model\Task $task
     */
    private function getRedirectFromTask(\ThomasWoehlke\Gtd\Domain\Model\Task $task){
        /** @var $logger \TYPO3\CMS\Core\Log\Logger */
        $logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager')->getLogger(__CLASS__);
        $langId=$this->getLanguageId();
        $logger->error($langId);
        $pid = $this->uriBuilder->getTargetPageUid();
        $this->uriBuilder->reset()->setArguments(array('L' => $langId))->setTargetPageUid($pid);
        $uri = $this->uriBuilder->uriFor('inbox', array(), 'Task');
        switch($task->getTaskState()){
            case Task::$TASK_STATES['inbox']:
                break;
            case Task::$TASK_STATES['today']:
                $uri = $this->uriBuilder->uriFor('today', array(), 'Task');
                break;
            case Task::$TASK_STATES['next']:
                $uri = $this->uriBuilder->uriFor('next', array(), 'Task');
                break;
            case Task::$TASK_STATES['waiting']:
                $uri = $this->uriBuilder->uriFor('waiting', array(), 'Task');
                break;
            case Task::$TASK_STATES['scheduled']:
                $uri = $this->uriBuilder->uriFor('scheduled', array(), 'Task');
                break;
            case Task::$TASK_STATES['someday']:
                $uri = $this->uriBuilder->uriFor('someday', array(), 'Task');
                break;
            case Task::$TASK_STATES['completed']:
                $uri = $this->uriBuilder->uriFor('completed', array(), 'Task');
                break;
            case Task::$TASK_STATES['trash']:
                $uri = $this->uriBuilder->uriFor('trash', array(), 'Task');
                break;
            default:
                break;
        }

        $logger->error($uri);
        $this->redirectToUri($uri);
    }

    public function initializeUpdateAction()
    {
        $this->arguments['task']
            ->getPropertyMappingConfiguration()
            ->forProperty('dueDate')
            ->setTypeConverterOption('TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter',
                \TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 'Y-m-d');
//        $pid = $this->uriBuilder->getTargetPageUid();
//        $this->uriBuilder->reset()->setArguments(array('L' => $GLOBALS['TSFE']->sys_language_uid))->setTargetPageUid($pid);
    }

    /**
     * action inbox
     *
     * @return void
     */
    public function inboxAction()
    {
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $tasks = $this->taskRepository->findByUserAccountAndTaskState($userObject,$currentContext,Task::$TASK_STATES['inbox']);
        $this->view->assign('tasks', $tasks);
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$currentContext);
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($currentContext));
        $this->view->assign('langKey',$this->getLanguageId());
    }

    /**
     * action today
     *
     * @return void
     */
    public function todayAction()
    {
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $this->updateTodayAndScheduledTaskStates();
        $tasks = $this->taskRepository->findByUserAccountAndTaskState($userObject,$currentContext,Task::$TASK_STATES['today']);
        $this->view->assign('tasks', $tasks);
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$currentContext);
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($currentContext));
        $this->view->assign('langKey',$this->getLanguageId());
    }

    /**
     * action next
     *
     * @return void
     */
    public function nextAction()
    {
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $tasks = $this->taskRepository->findByUserAccountAndTaskState($userObject,$currentContext,Task::$TASK_STATES['next']);
        $this->view->assign('tasks', $tasks);
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$currentContext);
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($currentContext));
        $this->view->assign('langKey',$this->getLanguageId());
    }

    /**
     * action waiting
     *
     * @return void
     */
    public function waitingAction()
    {
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $tasks = $this->taskRepository->findByUserAccountAndTaskState($userObject,$currentContext,Task::$TASK_STATES['waiting']);
        $this->view->assign('tasks', $tasks);
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$currentContext);
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($currentContext));
        $this->view->assign('langKey',$this->getLanguageId());
    }

    /**
     * action scheduled
     *
     * @return void
     */
    public function scheduledAction()
    {
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $this->updateTodayAndScheduledTaskStates();
        $tasks = $this->taskRepository->findByUserAccountAndTaskState($userObject,$currentContext,Task::$TASK_STATES['scheduled']);
        $this->view->assign('tasks', $tasks);
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$currentContext);
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($currentContext));
        $this->view->assign('langKey',$this->getLanguageId());
    }

    /**
     * action someday
     *
     * @return void
     */
    public function somedayAction()
    {
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $tasks = $this->taskRepository->findByUserAccountAndTaskState($userObject,$currentContext,Task::$TASK_STATES['someday']);
        $this->view->assign('tasks', $tasks);
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$currentContext);
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($currentContext));
        $this->view->assign('langKey',$this->getLanguageId());
    }

    /**
     * action completed
     *
     * @return void
     */
    public function completedAction()
    {
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $tasks = $this->taskRepository->findByUserAccountAndTaskState($userObject,$currentContext,Task::$TASK_STATES['completed']);
        $this->view->assign('tasks', $tasks);
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$currentContext);
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($currentContext));
        $this->view->assign('langKey',$this->getLanguageId());
    }

    /**
     * action trash
     *
     * @return void
     */
    public function trashAction()
    {
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $tasks = $this->taskRepository->findByUserAccountAndTaskState($userObject,$currentContext,Task::$TASK_STATES['trash']);
        $this->view->assign('tasks', $tasks);
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$currentContext);
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($currentContext));
        $this->view->assign('langKey',$this->getLanguageId());
    }

    /**
     * action focus
     *
     * @return void
     */
    public function focusAction()
    {
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $tasks = $this->taskRepository->findByUserAccountAndHasFocus($userObject,$currentContext);
        $this->view->assign('tasks', $tasks);
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$currentContext);
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($currentContext));
        $this->view->assign('langKey',$this->getLanguageId());
    }

    /**
     * action emptyTrash
     *
     * @return void
     */
    public function emptyTrashAction()
    {
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $tasks = $this->taskRepository->findByUserAccountAndTaskState($userObject,$currentContext,Task::$TASK_STATES['trash']);
        foreach($tasks as $task){
            $this->taskRepository->remove($task);
        }
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.task.trash_emptied', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->myRedirect('trash');
    }

    /**
     * action transformTaskIntoProject
     *
     * @param \ThomasWoehlke\Gtd\Domain\Model\Task $task
     * @return void
     */
    public function transformTaskIntoProjectAction(\ThomasWoehlke\Gtd\Domain\Model\Task $task)
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
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.task.task2project', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->myRedirect('show',$args,"Project");
    }

    /**
     * action completeTask
     *
     * @param \ThomasWoehlke\Gtd\Domain\Model\Task $task
     * @return void
     */
    public function completeTaskAction(\ThomasWoehlke\Gtd\Domain\Model\Task $task)
    {
        $task->changeTaskState(Task::$TASK_STATES['completed']);
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,Task::$TASK_STATES['completed']);
        $task->setOrderIdTaskState($maxTaskStateOrderId);
        $this->taskRepository->update($task);
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.task.completed', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->getRedirectFromTask($task);
    }

    /**
     * action undoneTask
     *
     * @param \ThomasWoehlke\Gtd\Domain\Model\Task $task
     * @return void
     */
    public function undoneTaskAction(\ThomasWoehlke\Gtd\Domain\Model\Task $task)
    {
        $task->setToLastTaskState();
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,$task->getTaskState());
        $task->setOrderIdTaskState($maxTaskStateOrderId);
        $this->taskRepository->update($task);
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.task.notcompleted', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->getRedirectFromTask($task);
    }

    /**
     * action setFocus
     *
     * @param \ThomasWoehlke\Gtd\Domain\Model\Task $task
     * @return void
     */
    public function setFocusAction(\ThomasWoehlke\Gtd\Domain\Model\Task $task)
    {
        $task->setFocus(true);
        $this->taskRepository->update($task);
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.task.focus', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->getRedirectFromTask($task);
    }

    /**
     * action unsetFocus
     *
     * @param \ThomasWoehlke\Gtd\Domain\Model\Task $task
     * @return void
     */
    public function unsetFocusAction(\ThomasWoehlke\Gtd\Domain\Model\Task $task)
    {
        $task->setFocus(false);
        $this->taskRepository->update($task);
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.task.notfocus', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->getRedirectFromTask($task);
    }

    private function getTaskEnergyAndTaskTime(){
        $taskEnergy = array();
        $taskTime = array();
        switch ($this->getLanguage()) {
            case 'de':
                $taskEnergy = array(
                    0 => 'nichts',
                    1 => 'niedrig',
                    2 => 'mittel',
                    3 => 'hoch'
                );
                $taskTime = array(
                    0 => 'nichts',
                    1 => '5 min',
                    2 => '10 min',
                    3 => '15 min',
                    4 => '30 min',
                    5 => '45 min',
                    6 => '1 Stunde',
                    7 => '2 Stunden',
                    8 => '3 Stunden',
                    9 => '4 Stunden',
                    10 => '6 Stunden',
                    11 => '8 Stunden',
                    12 => 'mehr'
                );
                break;
            case 'en':
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
                break;
        }
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
        $ctx = $this->contextService->getCurrentContext();
        $this->getTaskEnergyAndTaskTime();
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$ctx);
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($ctx));
        $this->view->assign('langKey',$this->getLanguageId());
    }

    /**
     * action create
     *
     * @param \ThomasWoehlke\Gtd\Domain\Model\Task $newTask
     * @return void
     */
    public function createAction(\ThomasWoehlke\Gtd\Domain\Model\Task $newTask)
    {
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $newTask->setContext($currentContext);
        $newTask->setUserAccount($userObject);
        $newTask->setTaskState(Task::$TASK_STATES['inbox']);
        $projectOrderId = $this->taskRepository->getMaxProjectOrderId(null);
        $newTask->setOrderIdProject($projectOrderId);
        $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,Task::$TASK_STATES['inbox']);
        $newTask->setOrderIdTaskState($maxTaskStateOrderId);
        if($this->request !== null) {
            if ($this->request->hasArgument('file')) {
                $newTask->setFiles(str_replace('uploads/tx_gtd/', '', $this->request->getArgument('file')));
            }
        }
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.task.new', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        if($newTask->getDueDate() != NULL){
            $newTask->setTaskState(Task::$TASK_STATES['scheduled']);
            $this->taskRepository->add($newTask);
            $this->myRedirect('scheduled');
        } else {
            $newTask->setTaskState(Task::$TASK_STATES['inbox']);
            $this->taskRepository->add($newTask);
            $this->myRedirect('inbox');
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
     * @param \ThomasWoehlke\Gtd\Domain\Model\Task $task
     * @return void
     */
    public function moveToInboxAction(\ThomasWoehlke\Gtd\Domain\Model\Task $task){
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,Task::$TASK_STATES['inbox']);
        $task->setOrderIdTaskState($maxTaskStateOrderId);
        $task->changeTaskState(Task::$TASK_STATES['inbox']);
        $this->taskRepository->update($task);
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.task.moved_inbox', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
//        $this->redirect('inbox');
        $this->getRedirectFromTask($task);
    }

    /**
     * action moveToToday
     *
     * @param \ThomasWoehlke\Gtd\Domain\Model\Task $task
     * @return void
     */
    public function moveToTodayAction(\ThomasWoehlke\Gtd\Domain\Model\Task $task){
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,Task::$TASK_STATES['today']);
        $task->setOrderIdTaskState($maxTaskStateOrderId);
        $task->changeTaskState(Task::$TASK_STATES['today']);
        $this->taskRepository->update($task);
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.task.moved_today', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
//        $this->redirect('today');
        $this->getRedirectFromTask($task);
    }

    /**
     * action moveToNext
     *
     * @param \ThomasWoehlke\Gtd\Domain\Model\Task $task
     * @return void
     */
    public function moveToNextAction(\ThomasWoehlke\Gtd\Domain\Model\Task $task){
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,Task::$TASK_STATES['next']);
        $task->setOrderIdTaskState($maxTaskStateOrderId);
        $task->changeTaskState(Task::$TASK_STATES['next']);
        $this->taskRepository->update($task);
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.task.moved_next', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
//        $this->redirect('next');
        $this->getRedirectFromTask($task);
    }

    /**
     * action moveToWaiting
     *
     * @param \ThomasWoehlke\Gtd\Domain\Model\Task $task
     * @return void
     */
    public function moveToWaitingAction(\ThomasWoehlke\Gtd\Domain\Model\Task $task){
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,Task::$TASK_STATES['waiting']);
        $task->setOrderIdTaskState($maxTaskStateOrderId);
        $task->changeTaskState(Task::$TASK_STATES['waiting']);
        $this->taskRepository->update($task);
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.task.moved_waiting', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
//        $this->redirect('waiting');
        $this->getRedirectFromTask($task);
    }

    /**
     * action moveToSomeday
     *
     * @param \ThomasWoehlke\Gtd\Domain\Model\Task $task
     * @return void
     */
    public function moveToSomedayAction(\ThomasWoehlke\Gtd\Domain\Model\Task $task){
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,Task::$TASK_STATES['someday']);
        $task->setOrderIdTaskState($maxTaskStateOrderId);
        $task->changeTaskState(Task::$TASK_STATES['someday']);
        $this->taskRepository->update($task);
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.task.moved_someday', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
//        $this->redirect('someday');
        $this->getRedirectFromTask($task);
    }

    /**
     * action moveToCompleted
     *
     * @param \ThomasWoehlke\Gtd\Domain\Model\Task $task
     * @return void
     */
    public function moveToCompletedAction(\ThomasWoehlke\Gtd\Domain\Model\Task $task){
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,Task::$TASK_STATES['completed']);
        $task->setOrderIdTaskState($maxTaskStateOrderId);
        $task->changeTaskState(Task::$TASK_STATES['completed']);
        $this->taskRepository->update($task);
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.task.moved_completed', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
//        $this->redirect('completed');
        $this->getRedirectFromTask($task);
    }

    /**
     * action moveToTrash
     *
     * @param \ThomasWoehlke\Gtd\Domain\Model\Task $task
     * @return void
     */
    public function moveToTrashAction(\ThomasWoehlke\Gtd\Domain\Model\Task $task){
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,Task::$TASK_STATES['trash']);
        $task->setOrderIdTaskState($maxTaskStateOrderId);
        $task->changeTaskState(Task::$TASK_STATES['trash']);
        $this->taskRepository->update($task);
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.task.moved_trash', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
//        $this->redirect('trash');
        $this->getRedirectFromTask($task);
    }

    /**
     * action moveAllCompletedToTrash
     *
     * @return void
     */
    public function moveAllCompletedToTrashAction(){
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $tasks = $this->taskRepository->findByUserAccountAndTaskState($userObject,$currentContext, Task::$TASK_STATES['completed']);
        $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userObject,$currentContext,Task::$TASK_STATES['trash']);
        foreach($tasks as $task){
            $task->changeTaskState(Task::$TASK_STATES['trash']);
            $task->setOrderIdTaskState($maxTaskStateOrderId);
            $this->taskRepository->update($task);
            $maxTaskStateOrderId++;
        }
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.task.moved_completed2trash', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->myRedirect('trash');
//        $this->getRedirectFromTask($task);
    }

    /**
     * action moveTaskOrder
     *
     * @param \ThomasWoehlke\Gtd\Domain\Model\Task $srcTask
     * @param \ThomasWoehlke\Gtd\Domain\Model\Task $targetTask
     * @return void
     */
    public function moveTaskOrderAction(\ThomasWoehlke\Gtd\Domain\Model\Task $srcTask,
                                        \ThomasWoehlke\Gtd\Domain\Model\Task $targetTask){
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
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
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.task.ordering', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->getRedirectFromTask($srcTask);
    }

    /**
     * action moveTaskOrderInsideProject
     *
     * @param \ThomasWoehlke\Gtd\Domain\Model\Task $srcTask
     * @param \ThomasWoehlke\Gtd\Domain\Model\Task $targetTask
     * @return void
     */
    public function moveTaskOrderInsideProjectAction(\ThomasWoehlke\Gtd\Domain\Model\Task $srcTask,
                                                     \ThomasWoehlke\Gtd\Domain\Model\Task $targetTask){
        /** @var $userObject \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
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
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.task.ordering', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->myRedirect('show',$args,'Project');
    }

    /**
     * action uploadFiles
     *
     * @return void
     */
    public function uploadFilesAction(){
        /** @var $logger \TYPO3\CMS\Core\Log\Logger */
        $logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager')->getLogger(__CLASS__);
        $logger->debug($_FILES['upl']);
        $allowed = array('png', 'jpg', 'gif','zip','doc', 'xls', 'csv', 'docx', 'xlsx', 'psd', 'rar', 'indd', 'ind', 'pdf');
        if(isset($_FILES['upl']) && $_FILES['upl']['error'] == UPLOAD_ERR_OK){
            $extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);
            if(!in_array(strtolower($extension), $allowed)){
                echo '{"status":"error"}';
                $msg = "File Type not allowed";
                $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                exit;
            }
            $filePath = PATH_site . 'uploads/tx_gtd/';
            if(!file_exists($filePath)){
                \TYPO3\CMS\Core\Utility\GeneralUtility::mkdir($filePath);
            }
            $originalName = $_FILES['upl']['name'];
            $targetName = $this->getGoodFilemane($originalName);
            if(file_exists(($filePath . $_FILES['upl']['name']))){
                $timestamp = time();
                if(\TYPO3\CMS\Core\Utility\GeneralUtility::upload_copy_move($_FILES['upl']['tmp_name'], $filePath.$timestamp.'_'.$targetName)){
                    echo 'uploads/tx_gtd/'.$timestamp.'_'.$targetName;
                    $logger->debug('uploads/tx_gtd/'.$timestamp.'_'.$_FILES['upl']['name']);
                    exit;
                }
            } else {
                if(\TYPO3\CMS\Core\Utility\GeneralUtility::upload_copy_move($_FILES['upl']['tmp_name'], $filePath.$targetName)){
                    echo 'uploads/tx_gtd/'.$targetName;
                    $logger->debug('uploads/tx_gtd/'.$_FILES['upl']['name']);
                    exit;
                }
            }
        } else {
            if(isset($_FILES['upl'])){
                $msg = 'Failed Upload: '.$_FILES['upl']['name'].' ';
                switch ($_FILES['upl']['error']){
                    case UPLOAD_ERR_INI_SIZE: $msg .= 'The uploaded file exceeds the upload_max_filesize directive in php.ini.'; break;
                    case UPLOAD_ERR_FORM_SIZE: $msg .= 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';  break;
                    case UPLOAD_ERR_PARTIAL: $msg .= 'The uploaded file was only partially uploaded.'; break;
                    case UPLOAD_ERR_NO_FILE: $msg .= 'No file was uploaded.'; break;
                    case UPLOAD_ERR_NO_TMP_DIR: $msg .= 'Missing a temporary folder.'; break;
                    case UPLOAD_ERR_CANT_WRITE: $msg .= 'Failed to write file to disk.'; break;
                    case UPLOAD_ERR_EXTENSION: $msg .= 'A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help.'; break;
                    default: $msg .= 'Errorcode: '.$_FILES['upl']['error']; break;
                }
                $logger->error($msg);
                $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            } else {
                $logger->error('NOT isset($_FILES[\'upl\'])');
            }
            exit;
        }
    }

    /**
     * @param string $oldFilename
     * @return string
     */
    private function getGoodFilemane($oldFilename){
        $oldFilename = str_replace(' ','_',$oldFilename);
        return $oldFilename;
    }

    private function updateTodayAndScheduledTaskStates(){
        $tasks = $this->taskRepository->getScheduledTasksOfCurrentDay();
        foreach ($tasks as $task){
            $userAccount = $task->getUserAccount();
            $context = $task->getContext();
            $maxTaskStateOrderId = $this->taskRepository->getMaxTaskStateOrderId($userAccount,$context,Task::$TASK_STATES['today']);
            $task->changeTaskState(Task::$TASK_STATES['today']);
            $task->setOrderIdTaskState($maxTaskStateOrderId);
            $this->taskRepository->update($task);
        }
    }

    /**
     * @return string
     */
    private function getLanguage(){

        $settings = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
        );

        return $settings['config.']['language'];
    }

    private function getLanguageId(){

        $settings = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
        );

        return $settings['config.']['sys_language_uid'];
    }

    /**
     * @param string $actionName
     * @param array $controllerArguments
     * @param string $controllerName
     */
    private function myRedirect($actionName='inbox',$controllerArguments=array(),$controllerName = 'Task'){
        $langId=$this->getLanguageId();
        $pid = $this->uriBuilder->getTargetPageUid();
        $this->uriBuilder->reset()->setArguments(array('L' => $langId))->setTargetPageUid($pid);
        $uri = $this->uriBuilder->uriFor($actionName, $controllerArguments,$controllerName);
        $this->redirectToUri($uri);
    }

}
