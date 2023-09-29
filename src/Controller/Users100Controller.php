<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Users100Controller extends AbstractController
{
    #[Route('/users100', name: 'app_users100')]
    public function index(ManagerRegistry $managerRegistry): Response
    {
        $entityManager = $managerRegistry->getManager();

        for ($i = 4; $i < 110; $i++) {
            $user = new User();
            $user->setEmail('user' . ($i + 1) . '@example.com');
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(password_hash('123', PASSWORD_BCRYPT)); // Set a secure password hashing method
            $user->setUsername('username' . ($i + 1));

            $entityManager->persist($user);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_system'); // Redirect to the desired page after registration
    }
    
}
