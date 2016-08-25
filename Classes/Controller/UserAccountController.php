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
 * Class UserAccountController
 *
 * @package ThomasWoehlke\Gtd\Controller
 */
class UserAccountController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * userMessageRepository
     *
     * @var \ThomasWoehlke\Gtd\Domain\Repository\UserMessageRepository
     * @inject
     */
    protected $userMessageRepository = null;

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

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $this->view->assign('thisUser', $userObject);
        $userAccounts = $this->userAccountRepository->findAll();
        $this->view->assign('userAccounts', $userAccounts);
        $userAccount2messages = array();
        foreach ($userAccounts as $userAccount){
            if($userAccount->getUid() != $userObject->getUid()){
                $nrMessages = $this->userMessageRepository->getNewMessagesFor($userAccount,$userObject);
                $userAccount2messages[$userAccount->getUid()]=$nrMessages;
            }
        }
        $currentContext = $this->contextService->getCurrentContext();
        $contextList = $this->contextService->getContextList();
        $rootProjects = $this->projectRepository->getRootProjects($currentContext);
        $this->view->assign('userAccount2messages', $userAccount2messages);
        $this->view->assign('contextList',$contextList);
        $this->view->assign('currentContext',$currentContext);
        $this->view->assign('rootProjects',$rootProjects);
        $this->view->assign('langKey',$this->getLanguageId());
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

        $id = 0;

        $settings = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
        );

        if(isset($settings['config.']['sys_language_uid']) &&
            ($settings['config.']['sys_language_uid'] !== null)){
            $id = $settings['config.']['sys_language_uid'];
        }

        return $id;
    }

    /**
     * @param string $actionName
     * @param array $controllerArguments
     * @param string $controllerName
     */
    private function myRedirect(
        $actionName='inbox',$controllerArguments=array(),$controllerName = 'UserAccount'){
        $langId=$this->getLanguageId();
        $pid = $this->uriBuilder->getTargetPageUid();
        $this->uriBuilder->reset()->setArguments(array('L' => $langId))->setTargetPageUid($pid);
        $uri = $this->uriBuilder->uriFor($actionName, $controllerArguments,$controllerName);
        $this->redirectToUri($uri);
    }

}
