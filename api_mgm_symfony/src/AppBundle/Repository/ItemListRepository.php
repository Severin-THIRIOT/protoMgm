<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * ItemListRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ItemListRepository extends \Doctrine\ORM\EntityRepository
{
    public function getArrayOfListsByUser($userId)
    {
        $qb = $this->createQueryBuilder('l')
            ->select('l')
            ->where('l.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);

        return $qb;
    }

    public function getArrayListById($id){
        $qb = $this->createQueryBuilder('l')
            ->select('l')
            ->where('l.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);

        return $qb;
    }
}