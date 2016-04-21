<?php
namespace ThomasWoehlke\TwSimpleworklist\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Thomas Woehlke <woehlke@faktura-berlin.de>
 */
class UserConfigControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \ThomasWoehlke\TwSimpleworklist\Controller\UserConfigController
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = $this->getMock(\ThomasWoehlke\TwSimpleworklist\Controller\UserConfigController::class, ['redirect', 'forward', 'addFlashMessage'], [], '', false);
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllUserConfigsFromRepositoryAndAssignsThemToView()
    {

        $allUserConfigs = $this->getMock(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class, [], [], '', false);

        $userConfigRepository = $this->getMock(\ThomasWoehlke\TwSimpleworklist\Domain\Repository\UserConfigRepository::class, ['findAll'], [], '', false);
        $userConfigRepository->expects(self::once())->method('findAll')->will(self::returnValue($allUserConfigs));
        $this->inject($this->subject, 'userConfigRepository', $userConfigRepository);

        $view = $this->getMock(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class);
        $view->expects(self::once())->method('assign')->with('userConfigs', $allUserConfigs);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }
}
