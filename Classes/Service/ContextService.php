<?php
namespace ThomasWoehlke\TwSimpleworklist\Service;

use ThomasWoehlke\TwSimpleworklist\Domain\Model\Context;
use ThomasWoehlke\TwSimpleworklist\Domain\Model\UserConfig;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Created by PhpStorm.
 * User: tw
 * Date: 17.04.16
 * Time: 10:37
 */
class ContextService implements \TYPO3\CMS\Core\SingletonInterface
{

    /**
     * contextRepository
     *
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Repository\ContextRepository
     * @inject
     */
    protected $contextRepository = null;

    /**
     * userAccountRepository
     *
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Repository\UserAccountRepository
     * @inject
     */
    protected $userAccountRepository = null;

    /**
     * userConfigRepository
     *
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Repository\UserConfigRepository
     * @inject
     */
    protected $userConfigRepository = null;
    
    /**
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function getContextList(){
        $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $contextList = $this->contextRepository->findAllByUserAccount($userObject);
        if($contextList->count() == 0){
            $this->createDefaultContexts($userObject);
            $contextList = $this->contextRepository->findAllByUserAccount($userObject);
        }
        return $contextList;
    }

    /**
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $userObject
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    private function createDefaultContexts(\ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $userObject)
    {
        $work = new Context();
        $private = new Context();
        $work->setNameDe("Arbeit");
        $work->setNameEn("Work");
        $work->setUserAccount($userObject);
        $private->setNameDe("Privat");
        $private->setNameEn("Private");
        $private->setUserAccount($userObject);
        $this->contextRepository->add($work);
        $this->contextRepository->add($private);
    }

    /**
     * @return \ThomasWoehlke\TwSimpleworklist\Domain\Model\Context
     */
    public function getCurrentContext()
    {
        $sessionData = $GLOBALS['TSFE']->fe_user->getKey('ses', 'tx_twsimpleworklist_fesessiondata');
        $contextUid = $sessionData['contextUid'];
        if($contextUid == null){
            $contextList = $this->getContextList();
            $userObject = $this->userAccountRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
            $userConfig = $this->userConfigRepository->findByUserAccount($userObject);
            if($userConfig == null){
                $userConfig2 = new UserConfig();
                $userConfig2->setUserAccount($userObject);
                $ctx = $contextList->getFirst();
                $userConfig2->setDefaultContext($ctx);
                //DebuggerUtility::var_dump($userConfig2);
                $this->userConfigRepository->add($userConfig2);
                $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
                $persistenceManager = $objectManager->get("TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager");
                $persistenceManager->persistAll();
                $userConfig = $this->userConfigRepository->findByUserAccount($userObject);
                //DebuggerUtility::var_dump($userConfig);
            }
            $defaultContext = $userConfig->getDefaultContext();
            $sessionData['contextUid'] = $defaultContext->getUid();
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'tx_twsimpleworklist_fesessiondata', $sessionData);
            $GLOBALS['TSFE']->fe_user->storeSessionData();
            return $defaultContext;
        } else {
            return $this->contextRepository->findByUid($contextUid);
        }
    }

    /**
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $context
     * @return void
     */
    public function setCurrentContext(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $context){
        $sessionData = $GLOBALS['TSFE']->fe_user->getKey('ses', 'tx_twsimpleworklist_fesessiondata');
        $sessionData['contextUid'] = $context->getUid();
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'tx_twsimpleworklist_fesessiondata', $sessionData);
        $GLOBALS['TSFE']->fe_user->storeSessionData();
    }

}