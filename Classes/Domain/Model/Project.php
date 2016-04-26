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
     * children
     * 
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\ThomasWoehlke\TwSimpleworklist\Domain\Model\Project>
     */
    protected $children = NULL;
    
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
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }
    
    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     * 
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->children = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }
    
    /**
     * Adds a Project
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Project $child
     * @return void
     */
    public function addChild(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Project $child)
    {
        $this->children->attach($child);
    }
    
    /**
     * Removes a Project
     * 
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Project $child
     * @return void
     */
    public function removeChild(
        \ThomasWoehlke\TwSimpleworklist\Domain\Model\Project $childToRemove)
    {
        $this->children->detach($childToRemove);
    }
    
    /**
     * Returns the children
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\ThomasWoehlke\TwSimpleworklist\Domain\Model\Project>
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * Sets the children
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\ThomasWoehlke\TwSimpleworklist\Domain\Model\Project> $children
     * @return void
     */
    public function setChildren(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $children)
    {
        $this->children = $children;
    }
    
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
    public function setParent(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Project $parent = null)
    {
        $this->parent = $parent;
    }
}
