<?php
namespace ThomasWoehlke\TwSimpleworklist\Service;

use \ThomasWoehlke\TwSimpleworklist\Domain\Model\Context;

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

    private function createDefaultContexts($userObject)
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

}