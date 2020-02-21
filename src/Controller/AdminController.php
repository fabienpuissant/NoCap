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
        //Si le formulaire est soumis
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            //Si l'utilisateur est dans la base de données
            if($this->repository->checkUser($data->getNom(), $data->getPrenom()) 
                || $this->repository->checkCode($data->getCode())){
                $user = $this->repository->setUserIn($data->getNom(), $data->getPrenom());

                //Si l'utilisateur est deja rentré, on renvoie une erreur
                if($user->getIsIn()){
                    return $this->render("AdminIndex.html.twig", [
                        'form' =>  $form->createView(),
                        'err' => 3,
                    ]);
                }

                //Sinon on dit à la base qu'il est rentré et on affiche un succes
                $user->setIsIn(true);
                $entityManager->flush();
                return $this->render("AdminIndex.html.twig", [
                    'form' =>  $form->createView(),
                    'err' => 2,
                ]);
            }
            //L'utilisateur n'est pas dans la base, on renvoie une erreur
            return $this->render("AdminIndex.html.twig",[
                'form' =>  $form->createView(),
                'err' => 1, 
                ]);
        }

        //Pas d'affichage d'eurreur ni succes si le form n'est pas validé
        return $this->render("AdminIndex.html.twig",[
            'form' =>  $form->createView(),
            'err' => 0, 
            ]);
    }
}


?>