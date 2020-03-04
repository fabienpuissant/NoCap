<?php

namespace App\Controller;


use \App\Repository\AdminRepository;
use \App\Entity\Admin;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AddAdminController extends AbstractController
{

    private $repository;

    public function __construct(AdminRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/addadmin", name = "addadmin" , methods = "GET|POST")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(UserPasswordEncoderInterface $encoder)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = new Admin();

        $password = $encoder->encodePassword($user, 'Van237');


        $user->setUsername("Van")
            ->setPassword($password)
            ->setRoles(["ROLE_PHOTOGRAPHE"]);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render("base.html.twig");
    }
}
