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
 * The repository for Contexts
 */
class ContextRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $userObject
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAllByUserAccount(\ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $userObject)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->equals('userAccount', $userObject)
        );
        return $query->execute();
    }
}
