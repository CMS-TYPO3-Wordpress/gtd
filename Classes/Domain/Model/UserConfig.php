<?php
namespace ThomasWoehlke\TwSimpleworklist\Domain\Model;

/***
 *
 * This file is part of the "SimpleWorklist" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2016 Thomas Woehlke <woehlke@faktura-berlin.de>, faktura gGmbH
 *
 ***/

/**
 * UserConfig
 */
class UserConfig extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * defaultContext
     *
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Model\Context
     */
    protected $defaultContext = null;

    /**
     * userAccount
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
     */
    protected $userAccount = null;

    /**
     * Returns the defaultContext
     *
     * @return \ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $defaultContext
     */
    public function getDefaultContext()
    {
        return $this->defaultContext;
    }

    /**
     * Sets the defaultContext
     *
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $defaultContext
     * @return void
     */
    public function setDefaultContext(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $defaultContext)
    {
        $this->defaultContext = $defaultContext;
    }

    /**
     * Returns the userAccount
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $userAccount
     */
    public function getUserAccount()
    {
        return $this->userAccount;
    }

    /**
     * Sets the userAccount
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $userAccount
     * @return void
     */
    public function setUserAccount(\TYPO3\CMS\Extbase\Domain\Model\FrontendUser $userAccount)
    {
        $this->userAccount = $userAccount;
    }
}
