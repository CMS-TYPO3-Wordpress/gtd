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

}
