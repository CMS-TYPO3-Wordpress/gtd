<?php
namespace ThomasWoehlke\TwSimpleworklist\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Thomas Woehlke <woehlke@faktura-berlin.de>
 */
class UserAccountTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getUserEmailReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getUserEmail()
        );

    }

    /**
     * @test
     */
    public function setUserEmailForStringSetsUserEmail()
    {
        $this->subject->setUserEmail('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'userEmail',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getUserPasswordReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getUserPassword()
        );

    }

    /**
     * @test
     */
    public function setUserPasswordForStringSetsUserPassword()
    {
        $this->subject->setUserPassword('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'userPassword',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getUserFullnameReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getUserFullname()
        );

    }

    /**
     * @test
     */
    public function setUserFullnameForStringSetsUserFullname()
    {
        $this->subject->setUserFullname('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'userFullname',
            $this->subject
        );

    }
}
