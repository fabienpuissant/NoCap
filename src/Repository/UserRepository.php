<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
    * @return User[] Returns an array of User objects
    */
    
    public function checkApiKey($value)
    {
        $user = $this->createQueryBuilder('u')
            ->andWhere('u.Apikey = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
        if(!empty($user)){
            return true;
        }
        else {
            return false;
        }
    }

    public function findUserByApiKey($apikey){
        $user = $this->createQueryBuilder('u')
            ->andWhere('u.Apikey = :val')
            ->setParameter('val', $apikey)
            ->getQuery()
            ->getResult()
        ;
        if(!empty($user)){
            return $user[0];
        }
        else {
            return null;
        }
    }

    public function checkUser($nom, $prenom){
       $user = $this->getUserFromName($nom, $prenom);
        if(!empty($user)){
            return true;
        }
        else {
            return false;
        }
    }

    public function setUserIn($nom, $prenom){
        return $this->getUserFromName($nom, $prenom);
    }

    public function getUserFromName($nom, $prenom){
        $user = $this->createQueryBuilder('u')
        ->andWhere('u.Nom = :nom')
        ->setParameter('nom', $nom)
        ->andWhere('u.Prenom = :prenom')
        ->setParameter('prenom', $prenom)
        ->getQuery()
        ->getResult()
        ;
        if(!empty($user)){
            return $user[0];
        }
        else {
            return false;
        }
    }


    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
