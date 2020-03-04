<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */

    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        if ($this->getUser()) {

            $user = $this->getUser();

            $roles = $user->getRoles();
            foreach ($roles as $role) {
                if ($role == "ROLE_ADMIN") {
                    return $this->redirectToRoute('index_admin');
                } else if ($role == "ROLE_PHOTOGRAPHE") {
                    return $this->redirectToRoute('photographe_upload');
                }
            }
            return $this->redirectToRoute('flux');
        }


        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }



    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
