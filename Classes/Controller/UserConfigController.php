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
 * UserConfigController
 */
class UserConfigController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * userConfigRepository
     *
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Repository\UserConfigRepository
     * @inject
     */
    protected $userConfigRepository = null;


    /**
     * userAccountRepository
     *
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Repository\UserAccountRepository
     * @inject
     */
    protected $userAccountRepository = null;

    /**
     * contextService
     *
     * @var \ThomasWoehlke\TwSimpleworklist\Service\ContextService
     * @inject
     */
    protected $contextService = null;

    /**
     * projectRepository
     *
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Repository\ProjectRepository
     * @inject
     */
    protected $projectRepository = null;
    
    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $userConfigs = $this->userConfigRepository->findAll();
        $this->view->assign('userConfigs', $userConfigs);
    }

    /**
     * action show
     *
     * @return void
     */
    public function showAction(){
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $this->view->assign('thisUser', $userObject);
        $userConfig = $this->userConfigRepository->findByUserAccount($userObject);
        $this->view->assign('userConfig',$userConfig);
        $currentContext = $this->contextService->getCurrentContext();
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$currentContext);
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($currentContext));
    }

    /**
     * action update
     *
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserConfig $userConfig
     * @return void
     */
    public function updateAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\UserConfig $userConfig){
        $persistentUserConfig = $this->userConfigRepository->findByUid($userConfig->getUid());
        $persistentUserConfig->setDefaultContext($userConfig->getDefaultContext());
        $this->userConfigRepository->update($persistentUserConfig);
        $this->contextService->setCurrentContext($userConfig->getDefaultContext());
        $this->redirect('show');
    }
}
