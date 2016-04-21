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
 * ProjectController
 */
class ProjectController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * projectRepository
     * 
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Repository\ProjectRepository
     * @inject
     */
    protected $projectRepository = null;
    
    /**
     * action show
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Project $project
     * @return void
     */
    public function showAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Project $project)
    {
        $this->view->assign('project', $project);
    }
    
    /**
     * action edit
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Project $project
     * @ignorevalidation $project
     * @return void
     */
    public function editAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Project $project)
    {
        $this->view->assign('project', $project);
    }
    
    /**
     * action update
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Project $project
     * @return void
     */
    public function updateAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Project $project)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->projectRepository->update($project);
        $this->redirect('list');
    }
    
    /**
     * action delete
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Project $project
     * @return void
     */
    public function deleteAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Project $project)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->projectRepository->remove($project);
        $this->redirect('list');
    }
    
    /**
     * action addNewRootProject
     * 
     * @return void
     */
    public function addNewRootProjectAction()
    {
        
    }
    
    /**
     * action addNewChildProject
     * 
     * @return void
     */
    public function addNewChildProjectAction()
    {
        
    }
    
    /**
     * action moveProject
     * 
     * @return void
     */
    public function moveProjectAction()
    {
        
    }
    
    /**
     * action getAllProjects
     * 
     * @return void
     */
    public function getAllProjectsAction()
    {
        
    }
    
    /**
     * action getRootProjects
     * 
     * @return void
     */
    public function getRootProjectsAction()
    {
        
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
    }
    
    /**
     * action new
     * 
     * @return void
     */
    public function newAction()
    {
        
    }
    
    /**
     * action create
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Project $newProject
     * @return void
     */
    public function createAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Project $newProject)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->projectRepository->add($newProject);
        $this->redirect('list');
    }
}
