<?php

namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use \App\Entity\User;
use \App\Repository\UserRepository;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;




class AcceptUser extends AbstractController
{
    /**
     * Send an email to the user with his ApiKey
     * @Route("/acceptuser", name = "mail" , methods = "GET|POST") 
     */
    public function mail(UserRepository $repository, \Swift_Mailer $mailer, UrlGeneratorInterface $urlGenerator)
    {

        $users = $repository->findAll();
        $n = 208;
        foreach ($users as $user) {
            //$user = $users[$i];
            var_dump($user->getEmail());
            if ($user->getEmail() != '') {

                $lien = "http://nocap.ddns.net:8000/log/" . $user->getApiKey();

                //Envoi du mail
                $message = (new \Swift_Message('Hello Email'))
                    ->setFrom('nocapwebsite@gmail.com')
                    ->setTo($user->getEmail())
                    ->setSubject('Confirmation')
                    ->setBody(
                        $this->renderView(
                            // templates/emails/registration.html.twig
                            'emails/email.html.twig',
                            [

                                'lien' => $lien

                            ]
                        ),
                        'text/html'
                    );
                $mailer->send($message);
            }
        }

        return $this->render("base.html.twig");
    }
}
