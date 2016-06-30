<?php
namespace ThomasWoehlke\Gtd\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Thomas Woehlke <woehlke@faktura-berlin.de>
 */
class ContextControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \ThomasWoehlke\Gtd\Controller\ContextController
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = $this->getMock(\ThomasWoehlke\Gtd\Controller\ContextController::class, ['redirect', 'forward', 'addFlashMessage'], [], '', false);
    }

    protected function tearDown()
    {
        parent::tearDown();
    }



    /**
     * @test
     */
    public function listActionFetchesAllContextsFromRepositoryAndAssignsThemToView()
    {

        $allContexts = $this->getMock(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class, [], [], '', false);

        $contextRepository = $this->getMock(\ThomasWoehlke\Gtd\Domain\Repository\ContextRepository::class, ['findAll'], [], '', false);
        $contextRepository->expects(self::once())->method('findAll')->will(self::returnValue($allContexts));
        $this->inject($this->subject, 'contextRepository', $contextRepository);

        $view = $this->getMock(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class);
        $view->expects(self::once())->method('assign')->with('contexts', $allContexts);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenContextToView()
    {
        $context = new \ThomasWoehlke\Gtd\Domain\Model\Context();

        $view = $this->getMock(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class);
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('context', $context);

        $this->subject->showAction($context);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenContextToContextRepository()
    {
        $context = new \ThomasWoehlke\Gtd\Domain\Model\Context();

        $contextRepository = $this->getMock(\ThomasWoehlke\Gtd\Domain\Repository\ContextRepository::class, ['add'], [], '', false);
        $contextRepository->expects(self::once())->method('add')->with($context);
        $this->inject($this->subject, 'contextRepository', $contextRepository);

        $this->subject->createAction($context);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenContextToView()
    {
        $context = new \ThomasWoehlke\Gtd\Domain\Model\Context();

        $view = $this->getMock(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class);
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('context', $context);

        $this->subject->editAction($context);
    }


    /**
     * @test
     */
    public function updateActionUpdatesTheGivenContextInContextRepository()
    {
        $context = new \ThomasWoehlke\Gtd\Domain\Model\Context();

        $contextRepository = $this->getMock(\ThomasWoehlke\Gtd\Domain\Repository\ContextRepository::class, ['update'], [], '', false);
        $contextRepository->expects(self::once())->method('update')->with($context);
        $this->inject($this->subject, 'contextRepository', $contextRepository);

        $this->subject->updateAction($context);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenContextFromContextRepository()
    {
        $context = new \ThomasWoehlke\Gtd\Domain\Model\Context();

        $contextRepository = $this->getMock(\ThomasWoehlke\Gtd\Domain\Repository\ContextRepository::class, ['remove'], [], '', false);
        $contextRepository->expects(self::once())->method('remove')->with($context);
        $this->inject($this->subject, 'contextRepository', $contextRepository);

        $this->subject->deleteAction($context);
    }
}
