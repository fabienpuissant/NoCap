<?php

namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use \App\Entity\User;
use \App\Repository\UserRepository;

use \App\Entity\Message;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class AcceptUser extends AbstractController
{
    /**
     * Send an email to the user with his ApiKey
     * @param User User to send mail
     * @Route("/admin/acceptuser", name = "mail" , methods = "GET|POST") 
     */
    public function mail(UserRepository $repository, \Swift_Mailer $mailer){
        $user = $repository->getUserFromName('test', 'test');

        $lien = "localhost:8000/log/".$user->getApiKey();

        //Envoi du mail
        $message = (new \Swift_Message('Hello Email'))
        ->setFrom('nocapwebsite@gmail.com')
        ->setTo($user->getEmail())
        ->setBody(
            $this->renderView(
                // templates/emails/registration.html.twig
                'emails/email.html.twig',
                ['nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'lien' => $lien]
            ),
            'text/html'
            )
        ;
        $mailer->send($message);


        //Envoi du sms
        /*$sms = new Message(
            '+33638616084',
            'Votre invitation à la soirée NoCap à été approuvée. Votre lien pour vous connecter : '.$lien
        );
        $texter->send($sms);*/
        

        return $this->render("base.html.twig");

    }

}