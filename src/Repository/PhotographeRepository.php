<?php

namespace App\Repository;

use App\Entity\Photographe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Photographe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Photographe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Photographe[]    findAll()
 * @method Photographe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotographeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Photographe::class);
    }

    
    /**
     * @param string Email to check
     * @return bool 
     */
    public function checkValidEmail($email) 
    {
        $all_users = $this->findAll();
        foreach($all_users as $value)
        {
            if($value->getEmail() == $email)
            {
                return false;
            }
        }
        return true;
    }

    /**
     * @param string Password to check
     * @return bool 
     */
    public function checkValidMdp($mdp)
    {
        if(strlen($mdp) < 6)
        {
            return false;
        }
        else if(strtolower($mdp) == $mdp){
            return false;
        }
        else {
            return true;
        }
    }

    /**
     * @param String Password to hash
     * @return String Password_hashed
     */
    public function hash_password($password){
        return password_hash($password, PASSWORD_BCRYPT);
    }
        

    // /**
    //  * @return Photographe[] Returns an array of Photographe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Photographe
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
