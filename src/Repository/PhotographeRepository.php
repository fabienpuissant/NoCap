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

    /**
    *Check if the user has confirmed the email
    *@param String Email to check
    *@return boolval true if it is confirmed
    */
    public function is_confirmed($email){

      $emailfield = $this->findEmailEntry($email);
    
      if(empty($emailfield)) {
        return false;
    }
    else if($emailfield[0]->getIsConfirmed() == 1){
        return true;
    }
    return false;
  }

    /**
    *@param String The email to check the password
    *@param String Password to check
    *@return boolval true if the password matches
    */
    public function checkMdpFromEmail($email, $mdp){

      $emailfield = $this->findEmailEntry($email);
      
      if(empty($emailfield)) {
          return false;
      }
      else if(password_verify($mdp, $emailfield[0]->getPassword())){
          return true;
      }
      return false;
    }


    /**
    * @return Photographe[] Returns an array of Photographe objects
    */
    public function findEmailEntry($email)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.Email = :val')
            ->setParameter('val', $email)
            ->getQuery()
            ->getResult()
        ;
    }
    
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
