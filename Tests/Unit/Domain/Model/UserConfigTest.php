<?php
namespace ThomasWoehlke\TwSimpleworklist\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Thomas Woehlke <woehlke@faktura-berlin.de>
 */
class UserConfigTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserConfig
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserConfig();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }



    /**
     * @test
     */
    public function getDefaultContextReturnsInitialValueForContext()
    {
        self::assertEquals(
            null,
            $this->subject->getDefaultContext()
        );

    }

    /**
     * @test
     */
    public function setDefaultContextForContextSetsDefaultContext()
    {
        $defaultContextFixture = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\Context();
        $this->subject->setDefaultContext($defaultContextFixture);

        self::assertAttributeEquals(
            $defaultContextFixture,
            'defaultContext',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getUserAccountReturnsInitialValueForUserAccount()
    {
        self::assertEquals(
            null,
            $this->subject->getUserAccount()
        );

    }

    /**
     * @test
     */
    public function setUserAccountForUserAccountSetsUserAccount()
    {
        $userAccountFixture = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount();
        $this->subject->setUserAccount($userAccountFixture);

        self::assertAttributeEquals(
            $userAccountFixture,
            'userAccount',
            $this->subject
        );

    }
}
