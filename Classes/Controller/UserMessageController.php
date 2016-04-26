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
 * UserMessageController
 */
class UserMessageController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * userMessageRepository
     * 
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Repository\UserMessageRepository
     * @inject
     */
    protected $userMessageRepository = null;

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
     * action getLastMessagesBetweenCurrentAndOtherUser
     * 
     * @return void
     */
    public function getLastMessagesBetweenCurrentAndOtherUserAction()
    {
        
    }
    
    /**
     * action sendNewMessageToOtherUser
     * 
     * @return void
     */
    public function sendNewMessageToOtherUserAction()
    {
        
    }
    
    /**
     * action getAllMessagesBetweenCurrentAndOtherUser
     * 
     * @return void
     */
    public function getAllMessagesBetweenCurrentAndOtherUserAction()
    {
        
    }
    
    /**
     * action sendNewMessageToOtherUser2
     * 
     * @return void
     */
    public function sendNewMessageToOtherUser2Action()
    {
        
    }
    
    /**
     * action list
     * 
     * @return void
     */
    public function listAction(
        \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $thisUser,
        \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $otherUser)
    {
        $this->view->assign('thisUser', $thisUser);
        $this->view->assign('otherUser', $otherUser);
        $userMessages = $this->userMessageRepository->findAllBetweenTwoUsers($thisUser,$otherUser);
        $this->view->assign('userMessages', $userMessages);
        foreach ($userMessages as $msg){
            if((!$msg->isReadByReceiver())&&($msg->getReceiver()->getUid() == $thisUser->getUid())){
                $msg->setReadByReceiver(true);
                $this->userMessageRepository->update($msg);
            }
        }
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$this->contextService->getCurrentContext());
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($this->contextService->getCurrentContext()));
    }
    
    /**
     * action show
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserMessage $userMessage
     * @return void
     */
    public function showAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\UserMessage $userMessage)
    {
        $this->view->assign('userMessage', $userMessage);
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
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserMessage $newUserMessage
     * @return void
     */
    public function createAction(
        \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserMessage $newUserMessage,
        \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $sender,
        \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $receiver)
    {
        $newUserMessage->setReadByReceiver(false);
        $newUserMessage->setSender($sender);
        $newUserMessage->setReceiver($receiver);
        $this->userMessageRepository->add($newUserMessage);
        $arguments = array('thisUser'=> $sender,'otherUser' => $receiver);
        $controllerName = null;
        $extensionName = null;
        $this->redirect('list',$controllerName,$extensionName,$arguments);
    }
    
    /**
     * action edit
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserMessage $userMessage
     * @ignorevalidation $userMessage
     * @return void
     */
    public function editAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\UserMessage $userMessage)
    {
        $this->view->assign('userMessage', $userMessage);
    }
    
    /**
     * action update
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserMessage $userMessage
     * @return void
     */
    public function updateAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\UserMessage $userMessage)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->userMessageRepository->update($userMessage);
        $this->redirect('list');
    }
    
    /**
     * action delete
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserMessage $userMessage
     * @return void
     */
    public function deleteAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\UserMessage $userMessage)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->userMessageRepository->remove($userMessage);
        $this->redirect('list');
    }
}
