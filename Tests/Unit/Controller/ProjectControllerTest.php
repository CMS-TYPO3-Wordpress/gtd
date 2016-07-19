<?php
namespace ThomasWoehlke\Gtd\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Thomas Woehlke <woehlke@faktura-berlin.de>
 */
class ProjectControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \ThomasWoehlke\Gtd\Controller\ProjectController
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = $this->getMock(\ThomasWoehlke\Gtd\Controller\ProjectController::class, ['redirect', 'forward', 'addFlashMessage'], [], '', false);
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
    public function deleteActionTest(){

    }

    /**
     * @test
     */
    public function moveProjectActionTest(){

    }

    /**
     * @test
     */
    public function moveTaskActionTest(){

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

}
