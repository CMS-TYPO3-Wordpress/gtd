<?php

namespace ThomasWoehlke\Gtd\Command;

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

class TaskSchedulingController extends \TYPO3\CMS\Scheduler\Task\AbstractTask {

    protected $taskStates = array(
        'inbox' => 0, 'today' => 1, 'next' => 2, 'waiting' => 3, 'scheduled' => 4, 'someday' => 5, 'completed' => 6 , 'trash' => 7
    );

    /**
     * This is the main method that is called when a task is executed
     * It MUST be implemented by all classes inheriting from this one
     * Note that there is no error handling, errors and failures are expected
     * to be handled and logged by the client implementations.
     * Should return TRUE on successful execution, FALSE on error.
     *
     * @return bool Returns TRUE on successful execution, FALSE on error
     */
    public function execute()
    {
        /** @var $logger \TYPO3\CMS\Core\Log\Logger */
        $logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager')->getLogger(__CLASS__);

        $logger->error('execute Start');

        /** @var $objectManager \TYPO3\CMS\Extbase\Object\ObjectManager */
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');

        /** @var $taskRepository  \ThomasWoehlke\Gtd\Domain\Repository\TaskRepository */
        $taskRepository = $objectManager->get('ThomasWoehlke\\Gtd\\Domain\\Repository\\TaskRepository');

        /** @var $configurationManager \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface */
        $configurationManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface');

        $settings = $configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
        );

        $storagePid = $settings['plugin.']['tx_gtd_frontendgtd.']['persistence.']['storagePid'];

        $logger->error('storagePid: '.$storagePid);

        /** @var $querySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
        $querySettings = $objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');

        /** @var $persistenceManager \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager */
        $persistenceManager = $objectManager->get("TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager");

        $querySettings->setStoragePageIds(array($storagePid));

        $taskRepository->setDefaultQuerySettings($querySettings);

        $tasks = $taskRepository->getScheduledTasksOfCurrentDay();

        $logger->error('execute found: '.count($tasks));

        foreach ($tasks as $task){
            $userAccount = $task->getUserAccount();
            $context = $task->getContext();
            $maxTaskStateOrderId = $taskRepository->getMaxTaskStateOrderId($userAccount,$context,$this->taskStates['today']);
            $task->changeTaskState($this->taskStates['today']);
            $task->setOrderIdTaskState($maxTaskStateOrderId);
            $taskRepository->update($task);
            $logger->error($task->getTitle());
        }

        $persistenceManager->persistAll();

        $logger->error('execute DONE');

        return TRUE;
    }
}
