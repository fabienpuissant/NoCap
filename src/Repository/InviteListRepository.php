<?php

namespace App\Repository;

use App\Entity\InviteList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method InviteList|null find($id, $lockMode = null, $lockVersion = null)
 * @method InviteList|null findOneBy(array $criteria, array $orderBy = null)
 * @method InviteList[]    findAll()
 * @method InviteList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InviteListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InviteList::class);
    }

    // /**
    //  * @return InviteList[] Returns an array of InviteList objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InviteList
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
