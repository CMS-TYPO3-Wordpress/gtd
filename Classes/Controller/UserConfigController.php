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

/**
 * Class UserConfigController
 *
 * @package ThomasWoehlke\Gtd\Controller
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
        $userConfig = $this->userConfigRepository->findByUserAccount($userObject);
        $currentContext = $this->contextService->getCurrentContext();
        $contextList = $this->contextService->getContextList();
        $rootProjects = $this->projectRepository->getRootProjects($currentContext);
        $this->view->assign('thisUser', $userObject);
        $this->view->assign('userConfig',$userConfig);
        $this->view->assign('contextList',$contextList);
        $this->view->assign('currentContext',$currentContext);
        $this->view->assign('rootProjects',$rootProjects);
        $this->view->assign('langKey',$this->getLanguageId());
    }

    /**
     * action update
     *
     * @param \ThomasWoehlke\Gtd\Domain\Model\UserConfig $userConfig
     * @return void
     */
    public function updateAction(\ThomasWoehlke\Gtd\Domain\Model\UserConfig $userConfig){
        $persistentUserConfig = $this->userConfigRepository->findByUid($userConfig->getUid());
        $ctx = $userConfig->getDefaultContext();
        $persistentUserConfig->setDefaultContext($ctx);
        $this->userConfigRepository->update($persistentUserConfig);
        $this->contextService->setCurrentContext($ctx);
        $msg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_gtd_flash.userconfig.updated', $this->extName, null);
        $this->addFlashMessage($msg, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->myRedirect('show');
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
    private function myRedirect($actionName='show',$controllerArguments=array(),$controllerName = 'UserConfig'){
        $langId=$this->getLanguageId();
        $pid = $this->uriBuilder->getTargetPageUid();
        $this->uriBuilder->reset()->setArguments(array('L' => $langId))->setTargetPageUid($pid);
        $uri = $this->uriBuilder->uriFor($actionName, $controllerArguments,$controllerName);
        $this->redirectToUri($uri);
    }
}
