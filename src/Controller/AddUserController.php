<?php

namespace App\Controller;


use \App\Repository\UserRepository;
use \App\Entity\User;
use \App\Form\UserType;


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
     * @Route("/admin/adduser", name = "adduser" , methods = "GET|POST") 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();


        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if($this->repository->checkUser($data->getNom(), $data->getPrenom())){
                return $this->render("adduser.html.twig", [
                    'form' =>  $form->createView(),
                    'err' => 2
                ]);
            }

            $user->setApiKey(md5(microtime().rand()))
                ->setEmail(htmlentities($data->getEmail()))
                ->setNom(htmlentities($data->getNom()))
                ->setPrenom(htmlentities($data->getPrenom()))
                ->setPhone(htmlentities($data->getPhone()))
                ->setCode(htmlentities($data->getCode()));
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->render("adduser.html.twig", [
                'form' =>  $form->createView(),
                'err' => 1
            ]);
        }

       

        return $this->render("adduser.html.twig", [
            'form' =>  $form->createView(),
            'err' => 0
        ]);
    }
}


?>