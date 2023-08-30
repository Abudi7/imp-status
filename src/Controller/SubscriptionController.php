<?php

namespace App\Controller;

use App\Controller\EventsController; // Make sure to import the EventsController
use App\Entity\Subscription;
use App\Entity\System;
use App\Repository\SubscriptionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Psr\Log\LoggerInterface;

class SubscriptionController extends AbstractController
{
    private $logger;

    private $eventsController;

    public function __construct(LoggerInterface $logger, EventsController $eventsController)
    {
        $this->logger = $logger;
        $this->eventsController = $eventsController;
    }
    /*
    * It defines the route for the subscription page.
    * It injects three services via the method signature: Request, ManagerRegistry, and TokenStorageInterface.
    * It retrieves the currently logged-in user from the token storage interface.
    * It fetches all subscriptions for the current user from the database using the ManagerRegistry service.
    * It creates a subscription form using Symfony's form builder and sets up the form fields.
    * It handles form submissions and checks if the form is submitted and valid.
    * If the form is submitted and valid, it creates a new Subscription object, sets its properties, and saves it to the database.
    * It redirects the user to the subscription page and displays a success message.
    * It fetches all system statuses from the database using the ManagerRegistry service.
    * It renders the subscription page and passes in system statuses, subscriptions, and the subscription form.
    */
    #[Route('/subscription', name: 'app_subscription')]
public function index(Request $request, ManagerRegistry $managerRegistry, TokenStorageInterface $tokenStorageInterface, SubscriptionRepository $subscriptionRepository, EventsController $eventsController): Response
{
    $user = $tokenStorageInterface->getToken()->getUser();
    
    // Fetch all systems
    $em = $managerRegistry->getManager();
    $systems = $em->getRepository(System::class)->findAll();
    
    // Set the status for each system using the EventsController method
    foreach ($systems as $system) {
        $status = $eventsController->getSystemStatus($system->getId(), $managerRegistry);
        $system->setStatus($status);
    }
    
    // Fetch subscriptions for the current user
    $sub = $subscriptionRepository->findBy(['user' => $user]);
    
    // Make the findFutureMaintenanceEvents method available in the template
    $twig = $this->container->get('twig');
    $twig->addGlobal('findFutureMaintenanceEvents', [$eventsController, 'findFutureMaintenanceEvents']);

    return $this->render('subscription/index.html.twig', [
        'subs'=> $sub,
        'systems' => $systems,
        'findFutureMaintenanceEvents' => [$eventsController, 'findFutureMaintenanceEvents'], // Add this line

    ]);
}



    /* The unsubscribe method is responsible for removing a subscription from the database. */
    #[Route('/subscription/{id}/unsbuscribe', name: 'app_subscription_unsbuscribe')]
    public function unsubscribe($id , ManagerRegistry $entityManager, SubscriptionRepository $subscriptionRepository)
    {
        // Find the subscription to unsubscribe from
        $unsubscribe = $subscriptionRepository->find($id);

        // Get the entity manager
        $entityManager = $entityManager->getManager();

        // Remove the subscription from the database
        $entityManager->remove($unsubscribe);
        $entityManager->flush();

        // Display a success flash message
        $this->addFlash('success', 'You have successfully unsubscribed from the subscription.');

        // Redirect the user back to the subscription page
        return $this->redirectToRoute('app_subscription');
    }








    //     // Get the logged-in user
//     $user = $tokenStorageInterface->getToken()->getUser();
    
//     // Fetch the user's subscriptions
//     $subscriptions = $managerRegistry->getRepository(Subscription::class)->findBy(['user' => $user]);

//     // Create the subscription form
//     $form = $this->createFormBuilder()
//         ->add('systemStatus', EntityType::class, [
//             'class' => System::class,
//             'choice_label' => 'system',
//             'placeholder' => 'Select a system',
//         ])
//         ->add('subscribe', SubmitType::class, [
//             'label' => 'Subscribe',
//         ])
//         ->getForm();

//     $form->handleRequest($request);

//     if ($form->isSubmitted() && $form->isValid()) {
//         // Handle the form submission

//         // Get the selected system status
//         $systemStatus = $form->get('systemStatus')->getData();

//         if ($systemStatus) {
//             // Create a new subscription
//             $subscription = new Subscription();
//             $subscription->setUser($user);
//             $subscription->setSystem($systemStatus);
//             $subscription->setIsSubscribed(true);

//             // Save the subscription to the database
//             $em = $managerRegistry->getManager();
//             $em->persist($subscription);
//             $em->flush();
            
          

//             // Display a success flash message
//             $this->addFlash('success', 'You have successfully subscribed to ' . $systemStatus->getSystem());

//             // Redirect the user back to the subscription page
//             return $this->redirectToRoute('app_subscription');
//         }
//     }

//     // Fetch all system statuses
//     $systemStatuses = $managerRegistry->getRepository(System::class)->findAll();


//     // Render the subscription page with the necessary data
//     return $this->render('subscription/index.html.twig', [
//         'systemStatuses' => $systemStatuses,
//         'subscriptions' => $subscriptions,
//         'form' => $form->createView(),
//     ]);
// }
}

