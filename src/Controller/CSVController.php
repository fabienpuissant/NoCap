<?php

namespace App\Controller;


use \App\Repository\UserRepository;
use \App\Entity\User;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CSVController extends AbstractController
{


    /**
     * "@Route("/csv", name = "csv")"
     */
    public function csv()
    {
        $row = 1;
        $users = [];
        if (($handle = fopen("test2.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 208, ",")) !== FALSE) {
                $num = count($data);
                $users[] = explode(";", $data[0]);
                $row++;
            }
            fclose($handle);
        }

        $entityManager = $this->getDoctrine()->getManager();
        array_shift($users);
        array_shift($users);

        var_dump($users);

        foreach ($users as $user) {

            $userdb = new User();
            $userdb->setEmail($user[11])
                ->setApiKey(md5(microtime() . rand()))
                ->setNom($user[9])
                ->setPrenom($user[10])
                ->setPhone($user[12])
                ->setCode($user[13]);
            $entityManager->persist($userdb);
            $entityManager->flush();
        }



        return $this->render("base.html.twig");
    }
}
