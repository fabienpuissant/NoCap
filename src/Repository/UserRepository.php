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
     *@param string the apikey value
    * @return bool true if the user is in the database
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

    /**
     * @param string the apikey of the user to find
     * @return User the user searched or NULL if it does not extist
     */
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

    /**
     * @param string The first name of the user to search
     * @param string The laste name of the user to search
     * @return bool True if the user is in the database
     */
    public function checkUser($nom, $prenom){
       $user = $this->getUserFromName($nom, $prenom);
        if(!empty($user)){
            return true;
        }
        else {
            return false;
        }
    }


    /**
     * @param string The code of the user to search
     * @return bool True if the user is in the database
     */
    public function checkCode($code){
        $code = $this->createQueryBuilder('u')
        ->andWhere('u.Code = :code')
        ->setParameter('code', $code)
        ->getQuery()
        ->getResult()
        ;
        if(!empty($code)){
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Set boolean value is_in in the database to true which permit know if the user is already in the party
     * @param string First name of the user
     * @param string Last name of the user
     * @return bool True if the user is setted in, false if the user was not found
     */
    public function setUserIn($nom, $prenom){
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getUserFromName($nom, $prenom);
        if($user){
            $user->setIsIn(true);
            $entityManager->flush();
            return true;
        }
        else {
            return false;
        }
    }

    /**
     *@param string First name of the user to search
     *@param string Laste name of the user to search
     *@return User if it is found, NULL if it is not in the database    
     */
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
