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
 * UserAccount
 */
class UserAccount extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * userEmail
     * 
     * @var string
     */
    protected $userEmail = '';
    
    /**
     * userPassword
     * 
     * @var string
     */
    protected $userPassword = '';
    
    /**
     * userFullname
     * 
     * @var string
     */
    protected $userFullname = '';
    
    /**
     * Returns the userEmail
     * 
     * @return string $userEmail
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }
    
    /**
     * Sets the userEmail
     * 
     * @param string $userEmail
     * @return void
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;
    }
    
    /**
     * Returns the userPassword
     * 
     * @return string $userPassword
     */
    public function getUserPassword()
    {
        return $this->userPassword;
    }
    
    /**
     * Sets the userPassword
     * 
     * @param string $userPassword
     * @return void
     */
    public function setUserPassword($userPassword)
    {
        $this->userPassword = $userPassword;
    }
    
    /**
     * Returns the userFullname
     * 
     * @return string $userFullname
     */
    public function getUserFullname()
    {
        return $this->userFullname;
    }
    
    /**
     * Sets the userFullname
     * 
     * @param string $userFullname
     * @return void
     */
    public function setUserFullname($userFullname)
    {
        $this->userFullname = $userFullname;
    }
}
