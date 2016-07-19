<?php
namespace ThomasWoehlke\Gtd\Controller;

use ThomasWoehlke\Gtd\Domain\Model\Project;
use ThomasWoehlke\Gtd\Domain\Model\Task;

/***
 *
 * This file is part of the "Getting Things Done" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2016 Thomas Woehlke <woehlke@faktura-berlin.de>, faktura gGmbH
 *
 ***/

/**
 * ProjectController
 */
class ProjectController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * projectRepository
     *
     * @var \ThomasWoehlke\Gtd\Domain\Repository\ProjectRepository
     * @inject
     */
    protected $projectRepository = null;

    /**
     * taskRepository
     *
     * @var \ThomasWoehlke\Gtd\Domain\Repository\TaskRepository
     * @inject
     */
    protected $taskRepository = null;

    /**
     * contextService
     *
     * @var \ThomasWoehlke\Gtd\Service\ContextService
     * @inject
     */
    protected $contextService = null;

    /**
     * userAccountRepository
     *
     * @var \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository
     * @inject
     */
    protected $userAccountRepository = null;

    private $extName = 'gtd';

    /**
     * action show
     *
     * @param \ThomasWoehlke\Gtd\Domain\Model\Project $project
     * @return void
     */
    public function showAction(\ThomasWoehlke\Gtd\Domain\Model\Project $project=null)
    {
        $ctx = $this->contextService->getCurrentContext();
        $this->view->assign('project', $project);
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$ctx);
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($ctx));
        $tasks = null;
        $deleteable = false;
        if($project == null){
            $tasks = $this->taskRepository->findByRootProjectAndContext($ctx);
        } else {
            $tasks = $this->taskRepository->findByProject($project);
            if($tasks->count() == 0 && $project->getChildren()->count() == 0){
                $deleteable = true;
            }
        }
        $this->view->assign('tasks', $tasks);
        $this->view->assign('deleteable',$deleteable);
    }

    /**
     * action edit
     *
     * @param \ThomasWoehlke\Gtd\Domain\Model\Project $project
     * @ignorevalidation $project
     * @return void
     */
    public function editAction(\ThomasWoehlke\Gtd\Domain\Model\Project $project)
    {
        $this->view->assign('project', $project);
        $ctx = $this->contextService->getCurrentContext();
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$ctx);
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($this->contextService->getCurrentContext()));
    }

    /**
     * action update
     *
     * @param \ThomasWoehlke\Gtd\Domain\Model\Project $project
     * @return void
     */
    public function updateAction(\ThomasWoehlke\Gtd\Domain\Model\Project $project)
    {
        $this->projectRepository->update($project);
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.project.updated', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $args = array('project'=>$project);
        $this->redirect('show',null,null,$args);
    }

    /**
     * action delete
     *
     * @param \ThomasWoehlke\Gtd\Domain\Model\Project $project
     * @return void
     */
    public function deleteAction(\ThomasWoehlke\Gtd\Domain\Model\Project $project)
    {
        $parentProject = $project->getParent();
        $deleteable = false;
        $tasks = $this->taskRepository->findByProject($project);
        if($tasks->count() == 0 && $project->getChildren()->count() == 0){
            $deleteable = true;
        }
        if($deleteable) {
            $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.project.deleted', $this->extName, null);
            $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
            $this->projectRepository->remove($project);
        }
        $args = array('project'=>$parentProject);
        $this->redirect('show',null,null,$args);
    }

    /**
     * action moveProject
     *
     * @return void
     */
    public function moveProjectAction(
        \ThomasWoehlke\Gtd\Domain\Model\Project $srcProject,
        \ThomasWoehlke\Gtd\Domain\Model\Project $targetProject=null)
    {
        $context = $this->contextService->getCurrentContext();
        if($targetProject == null){
            $oldParent = $srcProject->getParent();
            if($oldParent != null){
                $oldParent->removeChild($srcProject);
                $this->projectRepository->update($oldParent);
            }
            $srcProject->setParent($targetProject);
            $srcProject->setContext($context);
            $this->projectRepository->update($srcProject);
        } else {
            $oldParent = $srcProject->getParent();
            if($oldParent != null){
                $oldParent->removeChild($srcProject);
                $this->projectRepository->update($oldParent);
            }
            $srcProject->setParent($targetProject);
            $srcProject->setContext($context);
            $this->projectRepository->update($srcProject);
            $targetProject->addChild($srcProject);
            $this->projectRepository->update($targetProject);
        }
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.project.moved', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $arguments = array("project" => $targetProject);
        $this->redirect('show',null,null, $arguments);
    }

    /**
     * action moveTask
     *
     * @param Task $srcTask
     * @param Project|null $targetProject
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function moveTaskAction(
        \ThomasWoehlke\Gtd\Domain\Model\Task $srcTask,
        \ThomasWoehlke\Gtd\Domain\Model\Project $targetProject=null
    ){
        $srcTask->setProject($targetProject);
        $projectOrderId = $this->taskRepository->getMaxProjectOrderId($targetProject);
        $srcTask->setOrderIdProject($projectOrderId);
        $this->taskRepository->update($srcTask);
        $arguments = array("project" => $targetProject);
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.task.moved2project', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->redirect('show',null,null, $arguments);
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $projects = $this->projectRepository->findAll();
        $this->view->assign('projects', $projects);
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$this->contextService->getCurrentContext());
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($this->contextService->getCurrentContext()));
    }

    /**
     * action new
     *
     * @param \ThomasWoehlke\Gtd\Domain\Model\Project $parentProject
     * @return void
     */
    public function newAction(\ThomasWoehlke\Gtd\Domain\Model\Project $parentProject=null)
    {
        $this->view->assign('parentProject', $parentProject);
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$this->contextService->getCurrentContext());
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($this->contextService->getCurrentContext()));
    }

    /**
     * action create
     *
     * @param \ThomasWoehlke\Gtd\Domain\Model\Project $newProject
     * @return void
     */
    public function createAction(\ThomasWoehlke\Gtd\Domain\Model\Project $newProject,
                                 \ThomasWoehlke\Gtd\Domain\Model\Project $parentProject=null)
    {
        $context = $this->contextService->getCurrentContext();
        $newProject->setParent($parentProject);
        $newProject->setContext($context);
        $this->projectRepository->add($newProject);
        if($parentProject != null){
            $parentProject->addChild($newProject);
            $this->projectRepository->update($parentProject);
        }
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.project.created', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $args = array('project'=>$parentProject);
        $this->redirect('show',null,null,$args);
    }

    /**
     * action createTestData
     * @return void
     */
    public function createTestDataAction()
    {
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $currentContext = $this->contextService->getCurrentContext();
        $testProject1 = new Project();
        $testProject1->setContext($currentContext);
        $testProject1->setUserAccount($userObject);
        $testProject1->setName("Project 1");
        $testProject1->setDescription("Description 1");
        $testProject1->setParent(null);

        $testProject2 = new Project();
        $testProject2->setContext($currentContext);
        $testProject2->setUserAccount($userObject);
        $testProject2->setName("Project 2");
        $testProject2->setDescription("Description 2");
        $testProject2->setParent(null);

        $testProject3 = new Project();
        $testProject3->setContext($currentContext);
        $testProject3->setUserAccount($userObject);
        $testProject3->setName("Project 3");
        $testProject3->setDescription("Description 3");
        $testProject3->setParent(null);

        $testProject1_1 = new Project();
        $testProject1_1->setContext($currentContext);
        $testProject1_1->setUserAccount($userObject);
        $testProject1_1->setName("Project 11");
        $testProject1_1->setDescription("Description 11");
        $testProject1_1->setParent($testProject1);

        $testProject1_2 = new Project();
        $testProject1_2->setContext($currentContext);
        $testProject1_2->setUserAccount($userObject);
        $testProject1_2->setName("Project 12");
        $testProject1_2->setDescription("Description 12");
        $testProject1_2->setParent($testProject1);

        $testProject1_3 = new Project();
        $testProject1_3->setContext($currentContext);
        $testProject1_3->setUserAccount($userObject);
        $testProject1_3->setName("Project 13");
        $testProject1_3->setDescription("Description 13");
        $testProject1_3->setParent($testProject1);

        $testProject1_3_1 = new Project();
        $testProject1_3_1->setContext($currentContext);
        $testProject1_3_1->setUserAccount($userObject);
        $testProject1_3_1->setName("Project 131");
        $testProject1_3_1->setDescription("Description 131");
        $testProject1_3_1->setParent($testProject1_3);

        $testProject1_3_2 = new Project();
        $testProject1_3_2->setContext($currentContext);
        $testProject1_3_2->setUserAccount($userObject);
        $testProject1_3_2->setName("Project 132");
        $testProject1_3_2->setDescription("Description 132");
        $testProject1_3_2->setParent($testProject1_3);


        $testProject1->addChild($testProject1_1);
        $testProject1->addChild($testProject1_2);
        $testProject1->addChild($testProject1_3);

        $testProject1_3->addChild($testProject1_3_1);
        $testProject1_3->addChild($testProject1_3_2);

        $this->projectRepository->add($testProject1);
        $this->projectRepository->add($testProject2);
        $this->projectRepository->add($testProject3);
        $this->projectRepository->add($testProject1_1);
        $this->projectRepository->add($testProject1_2);
        $this->projectRepository->add($testProject1_3);
        $this->projectRepository->add($testProject1_3_1);
        $this->projectRepository->add($testProject1_3_2);

        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.testdata.created', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);

        $this->redirect('inbox',"Task");
    }
}
