<?php

namespace App\Controller;


use \App\Repository\UserRepository;
use \App\Entity\User;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AddUserController extends AbstractController
{

    private $repository;

    public function __construct(UserRepository $repository) 
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/adduser", name = "adduser" , methods = "GET|POST") 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setApiKey(md5(microtime().rand()));
        var_dump($user->getApiKey());   
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render("base.html.twig");
    }
}


?>