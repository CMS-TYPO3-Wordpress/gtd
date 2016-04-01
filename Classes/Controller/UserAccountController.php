<?php
namespace ThomasWoehlke\TwSimpleworklist\Controller;

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
 * UserAccountController
 */
class UserAccountController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * userAccountRepository
     * 
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Repository\UserAccountRepository
     * @inject
     */
    protected $userAccountRepository = null;
    
    /**
     * action list
     * 
     * @return void
     */
    public function listAction()
    {
        $userAccounts = $this->userAccountRepository->findAll();
        $this->view->assign('userAccounts', $userAccounts);
    }
    
    /**
     * action editPassword
     * 
     * @return void
     */
    public function editPasswordAction()
    {
        
    }
    
    /**
     * action editUsername
     * 
     * @return void
     */
    public function editUsernameAction()
    {
        
    }
}
