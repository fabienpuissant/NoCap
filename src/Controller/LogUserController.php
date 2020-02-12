<?php

namespace App\Controller;


use \App\Repository\UserRepository;
use \App\Entity\User;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LogUserController extends AbstractController
{

    private $repository;
    private $session;

    public function __construct(UserRepository $repository, SessionInterface $session){
        $this->repository = $repository;
        $this->session = $session;
    }

    /**
     * @Route("log/{apikey}", name = "log")
     */
    public function log(Request $request, string $apikey){
        
        if($this->repository->checkApiKey($apikey)){
            $this->session->set('user', $apikey);

            return $this->redirectToRoute("flux");

        } else {
            return $this->redirectToRoute("flux");
        }

    }

}

?>