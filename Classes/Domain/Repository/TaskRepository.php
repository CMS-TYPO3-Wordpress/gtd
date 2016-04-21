<?php
namespace ThomasWoehlke\TwSimpleworklist\Domain\Repository;

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
 * The repository for Tasks
 */
class TaskRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    protected $defaultOrderings = array(
        'orderIdTaskState' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
    );

    /**
     * @param int $taskState
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByUserAccountAndTaskState(\ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $userObject, $taskState){
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('userAccount', $userObject),
                $query->equals('taskState', $taskState)
            )
        );
        return $query->execute();
    }

    /**
     * @param int $taskState
     * @return int
     */
    public function getMaxTaskStateOrderId(\ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $userObject, $taskState){
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('userAccount', $userObject),
                $query->equals('taskState', $taskState)
            )
        );
        $query->setOrderings(
            array(
                "orderIdTaskState" => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
            )
        );
        $query->setLimit(1);
        $result = $query->execute();
        $maxTaskStateOrderId = 0;
        if($result->count()>0){
            $task = $result->getFirst();
            $maxTaskStateOrderId = $task->getOrderIdTaskState();
        }
        $maxTaskStateOrderId++;
        return $maxTaskStateOrderId;
    }

    /**
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $userObject
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByUserAccountAndHasFocus(\ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $userObject)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('userAccount', $userObject),
                $query->equals('focus', true)
            )
        );
        return $query->execute();
    }

    /**
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $userObject
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $lowerTask
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $higherTask
     * @param int $taskState
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function getTasksToReorderByOrderIdTaskState(
        \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $userObject,
        \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $lowerTask,
        \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $higherTask,
        $taskState)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('userAccount', $userObject),
                $query->equals('taskState',$taskState),
                $query->greaterThan('orderIdTaskState',$lowerTask->getOrderIdTaskState()),
                $query->lessThan('orderIdTaskState',$higherTask->getOrderIdTaskState())
            )
        );
        return $query->execute();
    }

}
