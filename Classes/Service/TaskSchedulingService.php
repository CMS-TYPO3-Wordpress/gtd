<?php

namespace ThomasWoehlke\Gtd\Service;

/***
 *
 * This file is part of the "Getting Things Done" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2016 Thomas Woehlke <thomas@woehlke.org>, faktura gGmbH
 *
 ***/


class TaskSchedulingService  implements \TYPO3\CMS\Core\SingletonInterface {

    /**
     * taskRepository
     *
     * @var \ThomasWoehlke\Gtd\Domain\Repository\TaskRepository
     * @inject
     */
    protected $taskRepository = null;

    /**
     * userAccountRepository
     *
     * @var \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository
     * @inject
     */
    protected $userAccountRepository = null;

    /**
     * projectRepository
     *
     * @var \ThomasWoehlke\Gtd\Domain\Repository\ProjectRepository
     * @inject
     */
    protected $projectRepository = null;

    /**
     * contextService
     *
     * @var \ThomasWoehlke\Gtd\Service\ContextService
     * @inject
     */
    protected $contextService = null;

    /**
     * @return bool Returns TRUE on successful execution, FALSE on error
     */
    public function moveScheduledTasksToToday(){
        /** @var $logger \TYPO3\CMS\Core\Log\Logger */
        $logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager')->getLogger(__CLASS__);
        $logger->error('moveScheduledTasksToToday');
        if($this->taskRepository == null){
            $logger->error('DI not working');
            return FALSE;
        } else {
            $logger->error('DI is working');
            $tasks = $this->taskRepository->findAll();
            foreach ($tasks as $task){
                $logger->error('task: ');
            }
            $logger->error('moveScheduledTasksToToday: DONE');
            return TRUE;
        }
    }

}
