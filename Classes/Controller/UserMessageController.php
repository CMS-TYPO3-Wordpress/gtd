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
     * action list
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $thisUser
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $otherUser
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     *
     * @return void
     */
    public function listAction(
        \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $thisUser,
        \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $otherUser)
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
     * action create
     *
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserMessage $newUserMessage
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $sender
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $receiver
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     *
     * @return void
     */
    public function createAction(
        \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserMessage $newUserMessage,
        \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $sender,
        \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $receiver)
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

}
