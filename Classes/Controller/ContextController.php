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
 * ContextController
 */
class ContextController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * contextRepository
     * 
     * @var \ThomasWoehlke\TwSimpleworklist\Domain\Repository\ContextRepository
     * @inject
     */
    protected $contextRepository = null;
    
    /**
     * action switchContext
     * 
     * @return void
     */
    public function switchContextAction()
    {
        
    }
    
    /**
     * action getAllContextsForUser
     * 
     * @return void
     */
    public function getAllContextsForUserAction()
    {
        
    }
}
