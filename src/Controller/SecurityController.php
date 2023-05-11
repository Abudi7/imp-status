<?php

namespace App\Controller;
/**
 * This code defines a controller that handles two URL routes: "/login" and "/logout".
 * The "/login" route corresponds to a controller action that displays a login form to the user.
 * If the user submits an invalid login form, the action retrieves the last authentication error and the last username entered by the user.
 * The action then renders a Twig template that displays the login form, along with any error messages.
 * The "/logout" route corresponds to a controller action that logs out the user.
 * The action throws a LogicException with a message indicating that the method can be blank, as the logout process is handled by Symfony's security firewall.
 */

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
