<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login", methods={"GET", "POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()){
            return $this->redirectToRoute('default_accueil');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        if($error){
            $this->addFlash('warning', 'Erreur : identifiants invalides');
        }

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();



        return $this->render('security/login.html.twig', ['last_username' => $lastUsername]);
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout() {}
}
