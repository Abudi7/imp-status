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

class SubscriptionController extends AbstractController
{
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

