<?php

namespace App\Controller;


use \App\Repository\UserRepository;
use \App\Entity\User;
use \App\Form\UserSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/admin/index", name = "adminIndex" , methods = "GET|POST") 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request)
    {

        //Recherche dans la liste des invités
        $entityManager = $this->getDoctrine()->getManager();
        $user = new User();
        $form = $this->createForm(UserSearchType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if($this->repository->checkEmail($data->getEmail())){
                return $this->render("AdminIndex.html.twig", [
                    'form' =>  $form->createView(),
                    'err' => 2,
                ]);
            }
            return $this->render("AdminIndex.html.twig",[
                'form' =>  $form->createView(),
                'err' => 1, 
                ]);
        }
        return $this->render("AdminIndex.html.twig",[
            'form' =>  $form->createView(),
            'err' => 0, 
            ]);
    }
}


?>