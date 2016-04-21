<?php
namespace ThomasWoehlke\TwSimpleworklist\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Thomas Woehlke <woehlke@faktura-berlin.de>
 */
class UserAccountControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \ThomasWoehlke\TwSimpleworklist\Controller\UserAccountController
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = $this->getMock(\ThomasWoehlke\TwSimpleworklist\Controller\UserAccountController::class, ['redirect', 'forward', 'addFlashMessage'], [], '', false);
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllUserAccountsFromRepositoryAndAssignsThemToView()
    {

        $allUserAccounts = $this->getMock(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class, [], [], '', false);

        $userAccountRepository = $this->getMock(\ThomasWoehlke\TwSimpleworklist\Domain\Repository\UserAccountRepository::class, ['findAll'], [], '', false);
        $userAccountRepository->expects(self::once())->method('findAll')->will(self::returnValue($allUserAccounts));
        $this->inject($this->subject, 'userAccountRepository', $userAccountRepository);

        $view = $this->getMock(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class);
        $view->expects(self::once())->method('assign')->with('userAccounts', $allUserAccounts);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenUserAccountToView()
    {
        $userAccount = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount();

        $view = $this->getMock(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class);
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('userAccount', $userAccount);

        $this->subject->showAction($userAccount);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenUserAccountToUserAccountRepository()
    {
        $userAccount = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount();

        $userAccountRepository = $this->getMock(\ThomasWoehlke\TwSimpleworklist\Domain\Repository\UserAccountRepository::class, ['add'], [], '', false);
        $userAccountRepository->expects(self::once())->method('add')->with($userAccount);
        $this->inject($this->subject, 'userAccountRepository', $userAccountRepository);

        $this->subject->createAction($userAccount);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenUserAccountToView()
    {
        $userAccount = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount();

        $view = $this->getMock(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class);
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('userAccount', $userAccount);

        $this->subject->editAction($userAccount);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenUserAccountInUserAccountRepository()
    {
        $userAccount = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount();

        $userAccountRepository = $this->getMock(\ThomasWoehlke\TwSimpleworklist\Domain\Repository\UserAccountRepository::class, ['update'], [], '', false);
        $userAccountRepository->expects(self::once())->method('update')->with($userAccount);
        $this->inject($this->subject, 'userAccountRepository', $userAccountRepository);

        $this->subject->updateAction($userAccount);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenUserAccountFromUserAccountRepository()
    {
        $userAccount = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount();

        $userAccountRepository = $this->getMock(\ThomasWoehlke\TwSimpleworklist\Domain\Repository\UserAccountRepository::class, ['remove'], [], '', false);
        $userAccountRepository->expects(self::once())->method('remove')->with($userAccount);
        $this->inject($this->subject, 'userAccountRepository', $userAccountRepository);

        $this->subject->deleteAction($userAccount);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenUserAccountToView()
    {
        $userAccount = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount();

        $view = $this->getMock(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class);
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('userAccount', $userAccount);

        $this->subject->editAction($userAccount);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenUserAccountToView()
    {
        $userAccount = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount();

        $view = $this->getMock(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class);
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('userAccount', $userAccount);

        $this->subject->editAction($userAccount);
    }

}
