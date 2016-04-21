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
 * Project
 */
class Project extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * name
     * 
     * @var string
     */
    protected $name = '';
    
    /**
     * description
     * 
     * @var string
     */
    protected $description = '';
    
    /**
     * context
     * 
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Model\Context
     */
    protected $context = null;
    
    /**
     * userAccount
     * 
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount
     */
    protected $userAccount = null;
    
    /**
     * parent
     * 
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Model\Project
     */
    protected $parent = null;
    
    /**
     * Returns the name
     * 
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Sets the name
     * 
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * Returns the description
     * 
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Sets the description
     * 
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    /**
     * Returns the context
     * 
     * @return \ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $context
     */
    public function getContext()
    {
        return $this->context;
    }
    
    /**
     * Sets the context
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $context
     * @return void
     */
    public function setContext(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $context)
    {
        $this->context = $context;
    }
    
    /**
     * Returns the userAccount
     * 
     * @return \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $userAccount
     */
    public function getUserAccount()
    {
        return $this->userAccount;
    }
    
    /**
     * Sets the userAccount
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $userAccount
     * @return void
     */
    public function setUserAccount(\ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $userAccount)
    {
        $this->userAccount = $userAccount;
    }
    
    /**
     * Returns the parent
     * 
     * @return \ThomasWoehlke\TwSimpleworklist\Domain\Model\Project $parent
     */
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
     * Sets the parent
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Project $parent
     * @return void
     */
    public function setParent(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Project $parent)
    {
        $this->parent = $parent;
    }
}
