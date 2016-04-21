<?php
namespace ThomasWoehlke\TwSimpleworklist\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Thomas Woehlke <woehlke@faktura-berlin.de>
 */
class UserMessageControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \ThomasWoehlke\TwSimpleworklist\Controller\UserMessageController
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = $this->getMock(\ThomasWoehlke\TwSimpleworklist\Controller\UserMessageController::class, ['redirect', 'forward', 'addFlashMessage'], [], '', false);
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllUserMessagesFromRepositoryAndAssignsThemToView()
    {

        $allUserMessages = $this->getMock(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class, [], [], '', false);

        $userMessageRepository = $this->getMock(\ThomasWoehlke\TwSimpleworklist\Domain\Repository\UserMessageRepository::class, ['findAll'], [], '', false);
        $userMessageRepository->expects(self::once())->method('findAll')->will(self::returnValue($allUserMessages));
        $this->inject($this->subject, 'userMessageRepository', $userMessageRepository);

        $view = $this->getMock(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class);
        $view->expects(self::once())->method('assign')->with('userMessages', $allUserMessages);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenUserMessageToView()
    {
        $userMessage = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserMessage();

        $view = $this->getMock(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class);
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('userMessage', $userMessage);

        $this->subject->showAction($userMessage);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenUserMessageToUserMessageRepository()
    {
        $userMessage = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserMessage();

        $userMessageRepository = $this->getMock(\ThomasWoehlke\TwSimpleworklist\Domain\Repository\UserMessageRepository::class, ['add'], [], '', false);
        $userMessageRepository->expects(self::once())->method('add')->with($userMessage);
        $this->inject($this->subject, 'userMessageRepository', $userMessageRepository);

        $this->subject->createAction($userMessage);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenUserMessageToView()
    {
        $userMessage = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserMessage();

        $view = $this->getMock(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class);
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('userMessage', $userMessage);

        $this->subject->editAction($userMessage);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenUserMessageInUserMessageRepository()
    {
        $userMessage = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserMessage();

        $userMessageRepository = $this->getMock(\ThomasWoehlke\TwSimpleworklist\Domain\Repository\UserMessageRepository::class, ['update'], [], '', false);
        $userMessageRepository->expects(self::once())->method('update')->with($userMessage);
        $this->inject($this->subject, 'userMessageRepository', $userMessageRepository);

        $this->subject->updateAction($userMessage);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenUserMessageFromUserMessageRepository()
    {
        $userMessage = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserMessage();

        $userMessageRepository = $this->getMock(\ThomasWoehlke\TwSimpleworklist\Domain\Repository\UserMessageRepository::class, ['remove'], [], '', false);
        $userMessageRepository->expects(self::once())->method('remove')->with($userMessage);
        $this->inject($this->subject, 'userMessageRepository', $userMessageRepository);

        $this->subject->deleteAction($userMessage);
    }
}
