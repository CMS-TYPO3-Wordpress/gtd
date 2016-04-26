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
 * The repository for Projects
 */
class ProjectRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $currentContext
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function getRootProjects(\ThomasWoehlke\TwSimpleworklist\Domain\Model\Context $currentContext)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->useQueryCache(FALSE);
        $query->matching(
            $query->logicalAnd(
                $query->equals('context', $currentContext),
                $query->equals('parent', 0)
            )
        );
        return $query->execute();
    }
}
