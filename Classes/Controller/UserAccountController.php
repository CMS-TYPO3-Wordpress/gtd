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
     * userAccountRepository
     * 
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Repository\UserAccountRepository
     * @inject
     */
    protected $userAccountRepository = null;

    /**
     * userMessageRepository
     *
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Repository\UserMessageRepository
     * @inject
     */
    protected $userMessageRepository = null;

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
        $this->view->assign('userAccount2messages', $userAccount2messages);
    }
    
    /**
     * action editPassword
     * 
     * @return void
     */
    public function editPasswordAction()
    {
        
    }
    
    /**
     * action editUsername
     * 
     * @return void
     */
    public function editUsernameAction()
    {
        
    }
    
    /**
     * action show
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $userAccount
     * @return void
     */
    public function showAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $userAccount)
    {
        $this->view->assign('userAccount', $userAccount);
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
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $newUserAccount
     * @return void
     */
    public function createAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $newUserAccount)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->userAccountRepository->add($newUserAccount);
        $this->redirect('list');
    }
    
    /**
     * action edit
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $userAccount
     * @ignorevalidation $userAccount
     * @return void
     */
    public function editAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $userAccount)
    {
        $this->view->assign('userAccount', $userAccount);
    }
    
    /**
     * action update
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $userAccount
     * @return void
     */
    public function updateAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $userAccount)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->userAccountRepository->update($userAccount);
        $this->redirect('list');
    }
    
    /**
     * action delete
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $userAccount
     * @return void
     */
    public function deleteAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $userAccount)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->userAccountRepository->remove($userAccount);
        $this->redirect('list');
    }
}
