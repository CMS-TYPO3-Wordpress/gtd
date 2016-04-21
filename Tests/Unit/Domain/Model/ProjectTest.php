<?php
namespace ThomasWoehlke\TwSimpleworklist\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Thomas Woehlke <woehlke@faktura-berlin.de>
 */
class ProjectTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Model\Project
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\Project();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getNameReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getName()
        );

    }

    /**
     * @test
     */
    public function setNameForStringSetsName()
    {
        $this->subject->setName('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'name',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getDescriptionReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getDescription()
        );

    }

    /**
     * @test
     */
    public function setDescriptionForStringSetsDescription()
    {
        $this->subject->setDescription('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'description',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getContextReturnsInitialValueForContext()
    {
        self::assertEquals(
            null,
            $this->subject->getContext()
        );

    }

    /**
     * @test
     */
    public function setContextForContextSetsContext()
    {
        $contextFixture = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\Context();
        $this->subject->setContext($contextFixture);

        self::assertAttributeEquals(
            $contextFixture,
            'context',
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

    /**
     * @test
     */
    public function getParentReturnsInitialValueForProject()
    {
        self::assertEquals(
            null,
            $this->subject->getParent()
        );

    }

    /**
     * @test
     */
    public function setParentForProjectSetsParent()
    {
        $parentFixture = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\Project();
        $this->subject->setParent($parentFixture);

        self::assertAttributeEquals(
            $parentFixture,
            'parent',
            $this->subject
        );

    }
}
