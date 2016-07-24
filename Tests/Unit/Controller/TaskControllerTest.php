<?php
namespace ThomasWoehlke\Gtd\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Thomas Woehlke <woehlke@faktura-berlin.de>
 */
class TaskControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \ThomasWoehlke\Gtd\Controller\TaskController
     */
    protected $subject = null;
    protected $taskEnergy = null;
    protected $taskTime = null;
    protected $userLoggedIn = null;
    protected $userConfig = null;
    protected $currentContext = null;
    protected $contextList = null;
    protected $project1 = null;
    protected $project2 = null;
    protected $rootProjects = null;
    protected $task1 = null;

    protected function setUp()
    {
        $this->subject = $this->getMock(\ThomasWoehlke\Gtd\Controller\TaskController::class, ['redirect', 'forward', 'addFlashMessage','getRedirectFromTask'], [], '', false);
        $this->taskEnergy = array(
            0 => 'none',
            1 => 'low',
            2 => 'mid',
            3 => 'high'
        );
        $this->taskTime = array(
            0 => 'none',
            1 => '5 min',
            2 => '10 min',
            3 => '15 min',
            4 => '30 min',
            5 => '45 min',
            6 => '1 hours',
            7 => '2 hours',
            8 => '3 hours',
            9 => '4 hours',
            10 => '6 hours',
            11 => '8 hours',
            12 => 'more'
        );
        $this->userLoggedIn = new \TYPO3\CMS\Extbase\Domain\Model\FrontendUser('loggedinuser','fd85df6575');
        $this->userConfig = new \ThomasWoehlke\Gtd\Domain\Model\UserConfig();
        $this->currentContext = new \ThomasWoehlke\Gtd\Domain\Model\Context();
        $this->currentContext->setNameDe('Arbeit');
        $this->currentContext->setNameEn('Work');
        $this->contextList = [$this->currentContext];
        $this->userConfig->setUserAccount($this->userLoggedIn);
        $this->userConfig->setDefaultContext($this->currentContext);
        $this->project1 = new \ThomasWoehlke\Gtd\Domain\Model\Project();
        $this->project1->setName('p1');
        $this->project1->setDescription('d1');
        $this->project1->setContext($this->currentContext);
        $this->project1->setUserAccount($this->userLoggedIn);
        $this->project2 = new \ThomasWoehlke\Gtd\Domain\Model\Project();
        $this->project2->setName('p2');
        $this->project2->setDescription('d2');
        $this->project2->setContext($this->currentContext);
        $this->project2->setUserAccount($this->userLoggedIn);
        $this->rootProjects = array($this->project1,$this->project2);

        $this->task1 = new \ThomasWoehlke\Gtd\Domain\Model\Task();
        $this->task1->setContext($this->currentContext);
        $this->task1->setUserAccount($this->userLoggedIn);
        $this->task1->setProject($this->project1);
        $this->task1->setText('Task Description');
        $this->task1->setTitle('Do something!');

        $dbresult = array();
        $dbresult['uid'] = 1;

        $GLOBALS['TYPO3_DB'] = $this->getMock(\TYPO3\CMS\Core\Database\DatabaseConnection::class, array(), array(), '', false);
        $GLOBALS['TYPO3_DB']->expects(self::any())->method('exec_SELECTgetSingleRow')->will(self::returnValue($dbresult));
        $GLOBALS['TYPO3_DB']->expects(self::any())->method('fullQuoteStr')->will(self::returnValue('test'));

        $GLOBALS['TYPO3_LOADED_EXT'] = ['gtd'=>[]];
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function showActionTest(){

        //inject $contextService
        $contextService = $this->getMock(\ThomasWoehlke\Gtd\Service\ContextService::class, ['getCurrentContext','getContextList'], [], '', false);
        $contextService->expects(self::once())->method('getCurrentContext')->will(self::returnValue($this->currentContext));
        $contextService->expects(self::once())->method('getContextList')->will(self::returnValue($this->contextList));
        $this->inject($this->subject, 'contextService', $contextService);

        //inject $projectRepository
        $projectRepository = $this->getMock(\ThomasWoehlke\Gtd\Domain\Repository\ProjectRepository::class, ['getRootProjects'], [$this->rootProjects], '', false);
        $projectRepository->expects(self::once())->method('getRootProjects')->will(self::returnValue($this->rootProjects));
        $this->inject($this->subject, 'projectRepository', $projectRepository);

        $view = $this->getMock(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class);
        $view->expects(self::at(0))->method('assign')->withConsecutive(['task', $this->task1]);
        $view->expects(self::at(1))->method('assign')->withConsecutive(['taskEnergy', $this->taskEnergy]);
        $view->expects(self::at(2))->method('assign')->withConsecutive(['taskTime', $this->taskTime]);
        $view->expects(self::at(3))->method('assign')->withConsecutive(['contextList', $this->contextList]);
        $view->expects(self::at(4))->method('assign')->withConsecutive(['currentContext', $this->currentContext]);
        $view->expects(self::at(5))->method('assign')->withConsecutive(['rootProjects', $this->rootProjects]);

        $this->inject($this->subject, 'view', $view);

        $this->subject->showAction($this->task1);
    }

    /**
     * @test
     */
    public function editActionTest(){

        //inject $contextService
        $contextService = $this->getMock(\ThomasWoehlke\Gtd\Service\ContextService::class, ['getCurrentContext','getContextList'], [], '', false);
        $contextService->expects(self::once())->method('getCurrentContext')->will(self::returnValue($this->currentContext));
        $contextService->expects(self::once())->method('getContextList')->will(self::returnValue($this->contextList));
        $this->inject($this->subject, 'contextService', $contextService);

        //inject $projectRepository
        $projectRepository = $this->getMock(\ThomasWoehlke\Gtd\Domain\Repository\ProjectRepository::class, ['getRootProjects'], [$this->rootProjects], '', false);
        $projectRepository->expects(self::once())->method('getRootProjects')->will(self::returnValue($this->rootProjects));
        $this->inject($this->subject, 'projectRepository', $projectRepository);

        $view = $this->getMock(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class);
        $view->expects(self::at(0))->method('assign')->withConsecutive(['task', $this->task1]);
        $view->expects(self::at(1))->method('assign')->withConsecutive(['taskEnergy', $this->taskEnergy]);
        $view->expects(self::at(2))->method('assign')->withConsecutive(['taskTime', $this->taskTime]);
        $view->expects(self::at(3))->method('assign')->withConsecutive(['contextList', $this->contextList]);
        $view->expects(self::at(4))->method('assign')->withConsecutive(['currentContext', $this->currentContext]);
        $view->expects(self::at(5))->method('assign')->withConsecutive(['rootProjects', $this->rootProjects]);

        $this->inject($this->subject, 'view', $view);

        $this->subject->editAction($this->task1);
    }

    /**
     * @test
     */
    public function updateActionTest(){

        // inject userAccountRepository
        $userAccountRepository = $this->getMock(\TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository::class, ['findByUid'], [1], '', false);
        $userAccountRepository->expects(self::once())->method('findByUid')->will(self::returnValue($this->userLoggedIn));
        $this->inject($this->subject, 'userAccountRepository', $userAccountRepository);

        //inject $contextService
        $contextService = $this->getMock(\ThomasWoehlke\Gtd\Service\ContextService::class, ['getCurrentContext'], [], '', false);
        $contextService->expects(self::once())->method('getCurrentContext')->will(self::returnValue($this->currentContext));
        $this->inject($this->subject, 'contextService', $contextService);

        //inject $taskRepository
        $taskRepository = $this->getMock(\ThomasWoehlke\Gtd\Domain\Repository\TaskRepository::class, ['update','getMaxTaskStateOrderId','findByUid'], [$this->task1,null,1], '', false);
        $taskRepository->expects(self::once())->method('findByUid')->will(self::returnValue($this->task1));
        $taskRepository->expects(self::once())->method('update')->with($this->task1);
        $taskRepository->expects(self::once())->method('getMaxTaskStateOrderId')->will(self::returnValue(10));
        $this->inject($this->subject, 'taskRepository', $taskRepository);

        $this->subject->updateAction($this->task1);
    }

    /**
     * @test
     */
    public function inboxActionTest(){

    }

    /**
     * @test
     */
    public function todayActionTest(){

    }

    /**
     * @test
     */
    public function nextActionTest(){

    }

    /**
     * @test
     */
    public function waitingActionTest(){

    }

    /**
     * @test
     */
    public function scheduledActionTest(){

    }

    /**
     * @test
     */
    public function somedayActionTest(){

    }

    /**
     * @test
     */
    public function completedActionTest(){

    }

    /**
     * @test
     */
    public function trashActionTest(){

    }

    /**
     * @test
     */
    public function focusActionTest(){

    }

    /**
     * @test
     */
    public function emptyTrashActionTest(){

    }

    /**
     * @test
     */
    public function transformTaskIntoProjectActionTest(){

    }

    /**
     * @test
     */
    public function completeTaskActionTest(){

    }

    /**
     * @test
     */
    public function undoneTaskActionTest(){

    }

    /**
     * @test
     */
    public function setFocusActionTest(){

    }

    /**
     * @test
     */
    public function unsetFocusActionTest(){

    }

    /**
     * @test
     */
    public function listActionTest(){

    }

    /**
     * @test
     */
    public function newActionTest(){

    }

    /**
     * @test
     */
    public function createActionTest(){

    }

    /**
     * @test
     */
    public function moveToInboxActionTest(){

    }

    /**
     * @test
     */
    public function moveToTodayActionTest(){

    }

    /**
     * @test
     */
    public function moveToNextActionTest(){

    }

    /**
     * @test
     */
    public function moveToWaitingActionTest(){

    }

    /**
     * @test
     */
    public function moveToSomedayActionTest(){

    }

    /**
     * @test
     */
    public function moveToCompletedActionTest(){

    }

    /**
     * @test
     */
    public function moveToTrashActionTest(){

    }

    /**
     * @test
     */
    public function moveAllCompletedToTrashActionTest(){

    }

    /**
     * @test
     */
    public function moveTaskOrderActionTest(){

    }

    /**
     * @test
     */
    public function moveTaskOrderInsideProjectActionTest(){

    }


}
