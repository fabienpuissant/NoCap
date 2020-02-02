<?php

namespace App\Controller;

use App\Entity\Photographe;
use App\Form\PhotographeType;
use App\Repository\PhotographeRepository;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PhotographeInscriptionController extends AbstractController 
{

    private $repository;

    public function __construct(PhotographeRepository $repository)
    {
        $this->repository = $repository;
    }
 
    /**
     * @Route("photographe/inscription", name = "inscription", methods="GET|POST")
     * @@return \Symfony\Component\HttpFoundation\Response
     */
    public function inscription(Request $request)
    {
        
        $photographe = new Photographe();
        $form  = $this->createForm(PhotographeType::class, $photographe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $data = $form->getData();
    
            if($this->repository->checkValidMdp($data->getPassword()) && $this->repository->checkValidEmail($data->getEmail())) 
            {   
                $em = $this->getDoctrine()->getManager();
                $photographe->setPassword($this->repository->hash_password($data->getPassword()));
                $photographe->setEmail(htmlentities($data->getEmail()));
                $em->persist($data);
                $em->flush(); 
                return $this->redirection($form, 0);
            }
            return $this->redirection($form, 1);
        }
    return $this->redirection($form, 2);
    }


        
    /**
     * @param SignInType 
     * @param Integer Error to display 0 no error, 1 error
     */
    private function redirection($form, $err){
        return $this->render('inscription.html.twig', [
            'form' => $form->createView(),
            'err' => $err
        ]);
    }




}