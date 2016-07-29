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

/**
 * The repository for UserConfigs
 */
class UserConfigRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $userObject
     * @return \ThomasWoehlke\Gtd\Domain\Model\UserConfig
     */
    public function findByUserAccount(\TYPO3\CMS\Extbase\Domain\Model\FrontendUser $userObject)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->equals('userAccount', $userObject)
        );
        $query->setLimit(1);
        return $query->execute()->getFirst();
    }
}
