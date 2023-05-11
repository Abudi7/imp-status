<?php

namespace App\Controller;
        /**
         * This code defines a controller action that handles a GET request to the "/register" URL route.
         * It creates a new User entity and builds a form using Symfony's form builder.
         * The form has four fields: email, username, roles, and password (which is repeated for confirmation).
         * The email and username fields are of type TextType, and the roles field is of type ChoiceType.
         * The roles field has two options: Admin and User, which are selectable as checkboxes.
         * The password field is of type RepeatedType, which requires the user to enter the same password twice.
         * If the form is submitted and validated, the action encodes the password using the UserPasswordHasherInterface, sets the user's roles based on the form input,
         * saves the user to the database using Doctrine's EntityManager, and redirects the user to the login page.
         * Finally, the action renders a Twig template that displays the form.
         */

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface,ManagerRegistry $managerRegistry): Response
    {
        // User Entity Database 
        $user = new User();
        // Create Form Maunel 
    
        $form = $this->createFormBuilder($user)
    ->add('email', TextType::class, [
        'label' => 'Email',
        'attr' => [
            'class' => 'form-control'
        ]
    ])

    ->add('username', TextType::class, [
        'label' => 'Username',
        'attr' => [
            'class' => 'form-control'
        ]
    ])

    ->add('roles', ChoiceType::class, [
        'choices' => [
            'Admin' => 'ROLE_ADMIN',
            'User' => 'ROLE_USER',
        ],
        'multiple' => true,
        'expanded' => true,
        'choice_attr' => [
            'Admin' => ['data-color' => 'Red'],
            'User' => ['data-color' => 'Green'],
        ],
    ])
    ->add('password', RepeatedType::class, [
        'type' => PasswordType::class,
        'required' => true,
        'first_options' => [
            'label' => 'Password',
            'attr' => [
                'class' => 'form-control'
            ]
        ],
        'second_options' => [
            'label' => 'Repeat password',
            'attr' => [
                'class' => 'form-control'
            ]
        ]
    ])
    
    ->add('register', SubmitType::class, [
        'label' => 'Register',
        'attr' => [
            'class' => 'btn btn-primary'
        ]
    ])
    ->getForm();


    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Encode the password before storing it in the database
        $password = $userPasswordHasherInterface->hashPassword($user, $user->getPassword());
        $user->setPassword($password);

        // Set the user's roles
        $user->setRoles($form->get('roles')->getData());

        $entityManager = $managerRegistry->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // Redirect the user to the login page
        return $this->redirectToRoute('app_login');
    }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
