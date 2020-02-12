<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="index", methods={"GET"})
     */
    public function logout(SessionInterface $session)
    {
        
        $session->clear();
        return $this->render("index.html.twig");
    }
}

?>