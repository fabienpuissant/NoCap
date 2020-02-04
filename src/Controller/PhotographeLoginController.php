<?php

namespace App\Controller;

use App\Entity\Photographe;
use App\Form\LoginType;
use App\Repository\PhotographeRepository;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PhotographeLoginController extends AbstractController
{

    private $repository;

    public function __construct(PhotographeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("photographe/login", name = "login", methods="GET|POST")
     * @@return \Symfony\Component\HttpFoundation\Response
     */
    public function login(Request $request)
    {
        $photographe = new Photographe();

        $form  = $this->createForm(LoginType::class, $photographe);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
          if($this->repository->checkMdpFromEmail($data->getEmail(), $data->getPassword()))
          {

            if($this->repository->is_confirmed($data->getEmail())) 
            {
                //Login success
                return $this->redirectToRoute('login');
            }
                //Not confirmed
                return $this->redirection($form, 1);
            }
                //Wrong email or password
                return $this->redirection($form, 2);
        }
        
        //Not submitted or not valid
      return $this->redirection($form, 3);
    }



    /**
     * @param SignInType
     * @param Integer Error to display 0 no error, 1 error
     */
    private function redirection($form, $err){
        return $this->render('login.html.twig', [
            'form' => $form->createView(),
            'err' => $err
        ]);
    }




}
