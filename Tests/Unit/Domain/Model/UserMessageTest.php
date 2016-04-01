<?php
namespace ThomasWoehlke\TwSimpleworklist\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Thomas Woehlke <woehlke@faktura-berlin.de>
 */
class UserMessageTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserMessage
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserMessage();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }



    /**
     * @test
     */
    public function getMessageTextReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getMessageText()
        );

    }

    /**
     * @test
     */
    public function setMessageTextForStringSetsMessageText()
    {
        $this->subject->setMessageText('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'messageText',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getReadByReceiverReturnsInitialValueForBool()
    {
        self::assertSame(
            false,
            $this->subject->getReadByReceiver()
        );

    }

    /**
     * @test
     */
    public function setReadByReceiverForBoolSetsReadByReceiver()
    {
        $this->subject->setReadByReceiver(true);

        self::assertAttributeEquals(
            true,
            'readByReceiver',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getSenderReturnsInitialValueForUserAccount()
    {
        self::assertEquals(
            null,
            $this->subject->getSender()
        );

    }

    /**
     * @test
     */
    public function setSenderForUserAccountSetsSender()
    {
        $senderFixture = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount();
        $this->subject->setSender($senderFixture);

        self::assertAttributeEquals(
            $senderFixture,
            'sender',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getReceiverReturnsInitialValueForUserAccount()
    {
        self::assertEquals(
            null,
            $this->subject->getReceiver()
        );

    }

    /**
     * @test
     */
    public function setReceiverForUserAccountSetsReceiver()
    {
        $receiverFixture = new \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount();
        $this->subject->setReceiver($receiverFixture);

        self::assertAttributeEquals(
            $receiverFixture,
            'receiver',
            $this->subject
        );

    }
}
