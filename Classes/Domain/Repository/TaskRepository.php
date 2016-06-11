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
    public function findByUserAccountAndTaskState(\TYPO3\CMS\Extbase\Domain\Model\FrontendUser $userObject,
                                                  \ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $currentContext,
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
                                           \ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $currentContext,
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
        \ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $currentContext)
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
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $lowerTask
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $higherTask
     * @param int $taskState
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function getTasksToReorderByOrderIdTaskState(
        \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $userObject,
        \ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $currentContext,
        \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $lowerTask,
        \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $higherTask,
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

    public function findByProject(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Project $project=null)
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

    public function getMaxProjectOrderId(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Project $project=null)
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

    public function findByRootProjectAndContext($ctx)
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

    public function getTasksToReorderByOrderIdProject(
        \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $userObject,
        \ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $currentContext,
        \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $lowerTask,
        \ThomasWoehlke\TwSimpleworklist\Domain\Model\Task $higherTask,
        \ThomasWoehlke\TwSimpleworklist\Domain\Model\Project $project=null)
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

}
