<?php

namespace Levi9\EProjectBundle\Entity;

use Doctrine\ORM\EntityRepository;

class RowRepository extends EntityRepository
{
    /**
     * Get battery statistics
     *
     * @return array Assoc array of battery type and count
     */
    public function getStatistics()
    {
        return $this->createQueryBuilder('row')
            ->select('SUM(row.count) as cnt, row.type')
            ->groupBy('row.type')
            ->orderBy('cnt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Remove all rows
     *
     * @return int Num of removed rows
     */
    public function removeAll()
    {
        return $this->createQueryBuilder('row')->delete()->getQuery()->getResult();
    }
}
