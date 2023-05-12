<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Entity\SystemStatus;
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

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
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
public function index(Request $request, ManagerRegistry $managerRegistry, TokenStorageInterface $tokenStorageInterface): Response
{
    //set the looged user to user varible 
    $user = $tokenStorageInterface->getToken()->getUser();
    
    $subscriptions = $managerRegistry->getRepository(Subscription::class)->findBy(['user' => $user]);

    $form = $this->createFormBuilder()
        ->add('systemStatus', EntityType::class, [
            'class' => SystemStatus::class,
            'choice_label' => 'system',
            'placeholder' => 'Select a system',
        ])
        ->add('subscribe', SubmitType::class, [
            'label' => 'Subscribe',
        ])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $systemStatus = $form->get('systemStatus')->getData();

        if ($systemStatus) {
            $subscription = new Subscription();
            $subscription->setUser($user);
            $subscription->setSystemStatus($systemStatus);
            $subscription->setIsSubscribed(true);

            $em = $managerRegistry->getManager();
            $em->persist($subscription);
            $em->flush();

            $this->addFlash('success', 'You have successfully subscribed to ' . $systemStatus->getSystem());

            return $this->redirectToRoute('app_subscription');
        }
    }

    $systemStatuses = $managerRegistry->getRepository(SystemStatus::class)->findAll();

    return $this->render('subscription/index.html.twig', [
        'systemStatuses' => $systemStatuses,
        'subscriptions' => $subscriptions,
        'form' => $form->createView(),
    ]);
}

/* The unsubscribe method is responsible for removing a subscription from the database. */
#[Route('/subscription/{id}/unsbuscribe', name: 'app_subscription_unsbuscribe')]
public function unsubscribe($id , ManagerRegistry $entityManger, SubscriptionRepository $subscriptionRepository)
{

    $unsubscripition = $subscriptionRepository->find($id);

    $entityManger = $entityManger->getManager();

    $entityManger->remove($unsubscripition);
    $entityManger->flush();

    $this->addFlash('success', 'You have successfully unsubscribed from the subscription.');

    return $this->redirectToRoute('app_subscription');
}


}

