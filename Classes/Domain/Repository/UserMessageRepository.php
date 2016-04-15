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
 * The repository for UserMessages
 */
class UserMessageRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    protected $defaultOrderings = array(
        'uid' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
    );

    /**
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $thisUser
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $otherUser
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAllBetweenTwoUsers(\ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $thisUser,
                                           \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $otherUser)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalOr(
                $query->logicalAnd(
                    $query->equals('sender', $thisUser),
                    $query->equals('receiver', $otherUser)
                ),
                $query->logicalAnd(
                    $query->equals('sender', $otherUser),
                    $query->equals('receiver', $thisUser)
                )
            )
        );
        return $query->execute();
    }

    /**
     * @param \ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $userAccount
     * @return int
     */
    public function getNewMessagesFor(\ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount $userAccount)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('sender', $userAccount),
                $query->equals('readByReceiver', false)
            )
        );
        return $query->count();
    }
}
