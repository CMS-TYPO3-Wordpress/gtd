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
 * UserAccountController
 */
class UserAccountController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * userMessageRepository
     *
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Repository\UserMessageRepository
     * @inject
     */
    protected $userMessageRepository = null;

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
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $this->view->assign('thisUser', $userObject);
        $userAccounts = $this->userAccountRepository->findAll();
        $this->view->assign('userAccounts', $userAccounts);
        $userAccount2messages = array();
        foreach ($userAccounts as $userAccount){
            if($userAccount->getUid() != $userObject->getUid()){
                $nrMessages = $this->userMessageRepository->getNewMessagesFor($userAccount);
                $userAccount2messages[$userAccount->getUid()]=$nrMessages;
            }
        }
        $currentContext = $this->contextService->getCurrentContext();
        $this->view->assign('userAccount2messages', $userAccount2messages);
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$currentContext);
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($currentContext));
    }
    
}
