<?php
namespace ThomasWoehlke\Gtd\Controller;

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
 * UserConfigController
 */
class UserConfigController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * userConfigRepository
     *
     * @var \ThomasWoehlke\Gtd\Domain\Repository\UserConfigRepository
     * @inject
     */
    protected $userConfigRepository = null;


    /**
     * userAccountRepository
     *
     * @var \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository
     * @inject
     */
    protected $userAccountRepository = null;

    /**
     * contextService
     *
     * @var \ThomasWoehlke\Gtd\Service\ContextService
     * @inject
     */
    protected $contextService = null;

    /**
     * projectRepository
     *
     * @var \ThomasWoehlke\Gtd\Domain\Repository\ProjectRepository
     * @inject
     */
    protected $projectRepository = null;

    private $extName = 'gtd';

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
     * @param \ThomasWoehlke\Gtd\Domain\Model\UserConfig $userConfig
     * @return void
     */
    public function updateAction(\ThomasWoehlke\Gtd\Domain\Model\UserConfig $userConfig){
        $persistentUserConfig = $this->userConfigRepository->findByUid($userConfig->getUid());
        $persistentUserConfig->setDefaultContext($userConfig->getDefaultContext());
        $this->userConfigRepository->update($persistentUserConfig);
        $this->contextService->setCurrentContext($userConfig->getDefaultContext());
        //$this->addFlashMessage('The Default Context was changed.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.userconfig.updated', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->redirect('show');
    }
}
