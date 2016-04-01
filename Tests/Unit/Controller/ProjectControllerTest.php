<?php
namespace ThomasWoehlke\TwSimpleworklist\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Thomas Woehlke <woehlke@faktura-berlin.de>
 */
class ProjectControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \ThomasWoehlke\TwSimpleworklist\Controller\ProjectController
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = $this->getMock(\ThomasWoehlke\TwSimpleworklist\Controller\ProjectController::class, ['redirect', 'forward', 'addFlashMessage'], [], '', false);
    }

    protected function tearDown()
    {
        parent::tearDown();
    }



    /**
     * @test
     */
    public function showActionAssignsTheGivenProjectToView()
    {
        $project = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\Project();

        $view = $this->getMock(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class);
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('project', $project);

        $this->subject->showAction($project);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenProjectToView()
    {
        $project = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\Project();

        $view = $this->getMock(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class);
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('project', $project);

        $this->subject->editAction($project);
    }


    /**
     * @test
     */
    public function updateActionUpdatesTheGivenProjectInProjectRepository()
    {
        $project = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\Project();

        $projectRepository = $this->getMock(\ThomasWoehlke\TwSimpleworklist\Domain\Repository\ProjectRepository::class, ['update'], [], '', false);
        $projectRepository->expects(self::once())->method('update')->with($project);
        $this->inject($this->subject, 'projectRepository', $projectRepository);

        $this->subject->updateAction($project);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenProjectFromProjectRepository()
    {
        $project = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\Project();

        $projectRepository = $this->getMock(\ThomasWoehlke\TwSimpleworklist\Domain\Repository\ProjectRepository::class, ['remove'], [], '', false);
        $projectRepository->expects(self::once())->method('remove')->with($project);
        $this->inject($this->subject, 'projectRepository', $projectRepository);

        $this->subject->deleteAction($project);
    }
}
