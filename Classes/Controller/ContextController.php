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
 * ContextController
 */
class ContextController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * contextRepository
     *
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Repository\ContextRepository
     * @inject
     */
    protected $contextRepository = null;

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
     * userAccountRepository
     *
     * @var \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository
     * @inject
     */
    protected $userAccountRepository = null;

    /**
     * action switchContext
     *
     * @return void
     */
    public function switchContextAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $context)
    {
        $sessionData = $GLOBALS['TSFE']->fe_user->getKey('ses', 'tx_twsimpleworklist_fesessiondata');
        $sessionData['contextUid'] = $context->getUid();
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'tx_twsimpleworklist_fesessiondata', $sessionData);
        $GLOBALS['TSFE']->fe_user->storeSessionData();
        $this->redirect('inbox',"Task");
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $contexts = $this->contextRepository->findAll();
        $this->view->assign('contexts', $contexts);
    }

    /**
     * action show
     *
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $context
     * @return void
     */
    public function showAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $context)
    {
        $this->view->assign('context', $context);
    }

    /**
     * action new
     *
     * @return void
     */
    public function newAction()
    {
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$this->contextService->getCurrentContext());
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($this->contextService->getCurrentContext()));
    }

    /**
     * action create
     *
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $newContext
     * @return void
     */
    public function createAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $newContext)
    {
        $this->addFlashMessage('The object was created.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $newContext->setUserAccount($userObject);
        $this->contextRepository->add($newContext);
        $this->redirect('show','UserConfig');
    }

    /**
     * action edit
     *
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $context
     * @ignorevalidation $context
     * @return void
     */
    public function editAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $context)
    {
        $this->view->assign('mycontext', $context);
        $this->view->assign('contextList',$this->contextService->getContextList());
        $this->view->assign('currentContext',$this->contextService->getCurrentContext());
        $this->view->assign('rootProjects',$this->projectRepository->getRootProjects($this->contextService->getCurrentContext()));
    }

    /**
     * action update
     *
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $context
     * @return void
     */
    public function updateAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $ctx)
    {
        $this->addFlashMessage('The object was updated.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->contextRepository->update($ctx);
        $this->redirect('show','UserConfig');
    }

    /**
     * action delete
     *
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $context
     * @return void
     */
    public function deleteAction(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $context)
    {
        $this->addFlashMessage('The object was deleted.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->contextRepository->remove($context);
        $this->redirect('show','UserConfig');
    }
}
