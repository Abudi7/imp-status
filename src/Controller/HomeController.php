<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Entity\SystemStatus;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class HomeController extends AbstractController
{
    #[Route('/' ,name: 'app_home')]
    public function index()
    {
        return $this->render('home/welcome.html.twig', [
            
        ]);
    }
    #[Route('/home/{id}/subscribe', name: 'app_home_subscribe')]
    public function subscribe($id, Request $request, ManagerRegistry $registry)
    {
        // Retrieve the SystemStatus entity from the database
        $status = $registry->getRepository(SystemStatus::class)->find($id);
        if (!$status) {
            throw $this->createNotFoundException('The system status does not exist');
        }

        // Get the currently authenticated user
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to subscribe');
        }

        // Check if the user is already subscribed to the system
        $subscription = $registry->getRepository(Subscription::class)->findOneBy([
            'systemStatus' => $status,
            'user' => $user,
        ]);

        if ($request->getMethod() === 'POST') {
            // If the subscription checkbox was unchecked, delete the subscription record
            if (!$request->request->get('subscribe')) {
                if ($subscription) {
                    $em = $registry->getManager();
                    $em->remove($subscription);
                    $em->flush();
                }
                // Add a flash message to indicate that the subscription was cancelled
                $this->addFlash('success', 'You have cancelled your subscription to ' . $status->getSystem());

                return $this->redirectToRoute('app_allSubscription');
            }

            // If the subscription checkbox was checked, create a new subscription or update the existing one
            if (!$subscription) {
                $subscription = new Subscription();
                $subscription->setSystemStatus($status);
                $subscription->setUser($user);
            }
            $subscription->setIsSubscribed(true);

            $em = $registry->getManager();
            $em->persist($subscription);
            $em->flush();

            // Add a flash message to indicate that the subscription was successful
            $this->addFlash('success', 'You have subscribed to ' . $status->getSystem());
        }
        $message = 'You have subscribed to ' . $status->getSystem() . 'to Your subscription System site';
        // Render the subscription switch in the template
        // return $this->render('home/index.html.twig', [
        //     'statuses' => $status,
        //     'message' => $message,

        // ]);
        return $this->render('home/allSubscription.html.twig', [
            'statuses' => $em->getRepository(SystemStatus::class)->findAll(),
        ]);
    }

    // public function subscribe($id, Request $request, ManagerRegistry $managerRegistry, Security $security)
    // {
    //     $systemStatus = $managerRegistry->getRepository(SystemStatus::class)->find($id);
    //     if (!$systemStatus) {
    //         throw $this->createNotFoundException('System status not found');
    //     }
    //          // Get the currently authenticated user
    //          $user = $security->getUser();

    //     $subscription = new Subscription();
    //     $subscription->setSystemStatus($systemStatus);
    //     $subscription->setUser($user);
    //     $subscription->setIsSubscribed(true);

    //     $em = $managerRegistry->getManager();
    //     $em->persist($subscription);
    //     $em->flush();

    //     $this->addFlash('success', 'You have successfully subscribed to ' . $systemStatus->getSystem());

    //     return $this->redirectToRoute('app_subscription');
    // }

    #[Route('/allSubscription', name: 'app_allSubscription')]
    public function allSubscription(ManagerRegistry $managerRegistry): Response
    {
        // get the currently authenticated user
        $user = $this->getUser();

        // get the entity manager
        $em = $managerRegistry->getManager();

        // get the list of SystemStatus entities that the user has subscribed to
        //$statuses = $em->getRepository(SystemStatus::class)->findSubscribedByUser($user);

        // render the template with the list of SystemStatus entities in allSubscription.html.twig
        return $this->render('home/allSubscription.html.twig', [
            'statuses' => $em->getRepository(SystemStatus::class)->findAll(),
        ]);
    }

}
