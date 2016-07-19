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

    protected function setUp()
    {
        $this->subject = $this->getMock(\ThomasWoehlke\Gtd\Controller\TaskController::class, ['redirect', 'forward', 'addFlashMessage'], [], '', false);
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function showActionTest(){

    }

    /**
     * @test
     */
    public function editActionTest(){

    }

    /**
     * @test
     */
    public function updateActionTest(){

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
