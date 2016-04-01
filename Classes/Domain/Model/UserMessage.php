<?php
namespace ThomasWoehlke\TwSimpleworklist\Domain\Model;

/***
 *
 * This file is part of the "SimpleWorklist" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2016 
 *
 ***/

/**
 * UserMessage
 */
class UserMessage extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * messageText
     * 
     * @var string
     */
    protected $messageText = '';
    
    /**
     * readByReceiver
     * 
     * @var bool
     */
    protected $readByReceiver = false;
    
    /**
     * sender
     * 
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount
     */
    protected $sender = null;
    
    /**
     * receiver
     * 
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount
     */
    protected $receiver = null;
    
    /**
     * Returns the messageText
     * 
     * @return string $messageText
     */
    public function getMessageText()
    {
        return $this->messageText;
    }
    
    /**
     * Sets the messageText
     * 
     * @param string $messageText
     * @return void
     */
    public function setMessageText($messageText)
    {
        $this->messageText = $messageText;
    }
    
    /**
     * Returns the readByReceiver
     * 
     * @return bool $readByReceiver
     */
    public function getReadByReceiver()
    {
        return $this->readByReceiver;
    }
    
    /**
     * Sets the readByReceiver
     * 
     * @param bool $readByReceiver
     * @return void
     */
    public function setReadByReceiver($readByReceiver)
    {
        $this->readByReceiver = $readByReceiver;
    }
    
    /**
     * Returns the boolean state of readByReceiver
     * 
     * @return bool
     */
    public function isReadByReceiver()
    {
        return $this->readByReceiver;
    }
    
    /**
     * Returns the sender
     * 
     * @return \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $sender
     */
    public function getSender()
    {
        return $this->sender;
    }
    
    /**
     * Sets the sender
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $sender
     * @return void
     */
    public function setSender(\ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $sender)
    {
        $this->sender = $sender;
    }
    
    /**
     * Returns the receiver
     * 
     * @return \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $receiver
     */
    public function getReceiver()
    {
        return $this->receiver;
    }
    
    /**
     * Sets the receiver
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $receiver
     * @return void
     */
    public function setReceiver(\ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $receiver)
    {
        $this->receiver = $receiver;
    }
}
