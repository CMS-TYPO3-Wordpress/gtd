<?php
namespace ThomasWoehlke\Gtd\Domain\Repository;

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

use \ThomasWoehlke\Gtd\Domain\Model\Task;

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
    public function findByUserAccountAndTaskState(\TYPO3\CMS\Extbase\Domain\Model\FrontendUser $userObject,
                                                  \ThomasWoehlke\Gtd\Domain\Model\Context $currentContext,
                                                  $taskState){
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('userAccount', $userObject),
                $query->equals('context',$currentContext),
                $query->equals('taskState', $taskState)
            )
        );
        return $query->execute();
    }

    /**
     * @param int $taskState
     * @return int
     */
    public function getMaxTaskStateOrderId(\TYPO3\CMS\Extbase\Domain\Model\FrontendUser $userObject,
                                           \ThomasWoehlke\Gtd\Domain\Model\Context $currentContext,
                                           $taskState){
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('userAccount', $userObject),
                $query->equals('context',$currentContext),
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
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $userObject
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByUserAccountAndHasFocus(
        \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $userObject,
        \ThomasWoehlke\Gtd\Domain\Model\Context $currentContext)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('userAccount', $userObject),
                $query->equals('context',$currentContext),
                $query->equals('focus', true)
            )
        );
        return $query->execute();
    }

    /**
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $userObject
     * @param \ThomasWoehlke\Gtd\Domain\Model\Task $lowerTask
     * @param \ThomasWoehlke\Gtd\Domain\Model\Task $higherTask
     * @param int $taskState
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function getTasksToReorderByOrderIdTaskState(
        \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $userObject,
        \ThomasWoehlke\Gtd\Domain\Model\Context $currentContext,
        \ThomasWoehlke\Gtd\Domain\Model\Task $lowerTask,
        \ThomasWoehlke\Gtd\Domain\Model\Task $higherTask,
        $taskState)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('userAccount', $userObject),
                $query->equals('context',$currentContext),
                $query->equals('taskState',$taskState),
                $query->greaterThan('orderIdTaskState',$lowerTask->getOrderIdTaskState()),
                $query->lessThan('orderIdTaskState',$higherTask->getOrderIdTaskState())
            )
        );
        return $query->execute();
    }

    /**
     * @param \ThomasWoehlke\Gtd\Domain\Model\Project|null $project
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByProject(\ThomasWoehlke\Gtd\Domain\Model\Project $project=null)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->equals('project', $project)
        );
        $query->setOrderings(
            array(
                "orderIdProject" => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
            )
        );
        return $query->execute();
    }

    /**
     * @param \ThomasWoehlke\Gtd\Domain\Model\Project|null $project
     * @return int
     */
    public function getMaxProjectOrderId(\ThomasWoehlke\Gtd\Domain\Model\Project $project=null)
    {
        $query = $this->createQuery();
        if($project == null){
            $query->matching(
                $query->equals('project', 0)
            );
        } else {
            $query->matching(
                $query->equals('project', $project)
            );
        }
        $query->setOrderings(
            array(
                "orderIdProject" => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
            )
        );
        $query->setLimit(1);
        $result = $query->execute();

        $maxProjectOrderId = 0;
        if($result->count()>0){
            $task = $result->getFirst();
            $maxProjectOrderId = $task->getOrderIdProject();
        }
        $maxProjectOrderId++;
        return $maxProjectOrderId;
    }

    /**
     * @param \ThomasWoehlke\Gtd\Domain\Model\Context $ctx
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByRootProjectAndContext(\ThomasWoehlke\Gtd\Domain\Model\Context $ctx)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('context',$ctx),
                $query->equals('project', 0)
            )
        );
        $query->setOrderings(
            array(
                "orderIdProject" => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
            )
        );
        return $query->execute();
    }

    /**
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $userObject
     * @param \ThomasWoehlke\Gtd\Domain\Model\Context $currentContext
     * @param Task $lowerTask
     * @param Task $higherTask
     * @param \ThomasWoehlke\Gtd\Domain\Model\Project|null $project
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function getTasksToReorderByOrderIdProject(
        \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $userObject,
        \ThomasWoehlke\Gtd\Domain\Model\Context $currentContext,
        \ThomasWoehlke\Gtd\Domain\Model\Task $lowerTask,
        \ThomasWoehlke\Gtd\Domain\Model\Task $higherTask,
        \ThomasWoehlke\Gtd\Domain\Model\Project $project=null)
    {
        $query = $this->createQuery();
        if($project == null){
            $query->matching(
                $query->logicalAnd(
                    $query->equals('userAccount', $userObject),
                    $query->equals('context',$currentContext),
                    $query->equals('project',0),
                    $query->greaterThan('orderIdProject',$lowerTask->getOrderIdProject()),
                    $query->lessThan('orderIdProject',$higherTask->getOrderIdProject())
                )
            );
        } else {
            $query->matching(
                $query->logicalAnd(
                    $query->equals('userAccount', $userObject),
                    $query->equals('context',$currentContext),
                    $query->equals('project',$project),
                    $query->greaterThan('orderIdProject',$lowerTask->getOrderIdProject()),
                    $query->lessThan('orderIdProject',$higherTask->getOrderIdProject())
                )
            );
        }
        return $query->execute();
    }

    /**
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function getScheduledTasksOfCurrentDay(){
        $today = new \DateTime();
        $today->setTime(0,0,0);
        $query = $this->createQuery();
        $query->setOrderings(
            array(
                "orderIdTaskState" => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
            )
        );
        $query->matching(
            $query->logicalAnd(
                $query->equals('taskState',Task::$TASK_STATES['scheduled']),
                $query->equals('dueDate', $today->format('Y-m-d'))
            )
        );
        return $query->execute();
    }

    /**
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function getTasksWithFiles(){
        $query = $this->createQuery();
        $query->matching(
            $query->logicalNot(
                $query->logicalOr(
                    $query->equals('files',null),
                    $query->equals('files','')
                )
            )
        );
        return $query->execute();
    }

}
