<?php
/**
 * Created by PhpStorm.
 * User: PC-RAFA
 * Date: 11/06/2018
 * Time: 14:59
 */

namespace App\Repository;


use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{

    public function findEvent($center, $now)
    {
        $query = $this->createQueryBuilder('e')
            ->where('e.date_end >= :now')
            ->andWhere('e.center = :center')
            ->andWhere('e.is_published = :true')
            ->orderBy('e.created', 'DESC')
            ->setParameter('center', $center->getId())
            ->setParameter('true', true)
            ->setParameter('now', $now)
            ->getQuery();

            return $query->getResult();
    }

}