<?php

namespace App\Controller;

use App\Controller\EventsController;
use App\Entity\Events;
use App\Entity\Subscription;
use App\Entity\System;
use App\Entity\SystemStatus;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * Homepage Controller
     *
     * This method handles the rendering of the homepage. When a user accesses the root URL ("/"),
     * this method is triggered, and it renders the "welcome.html.twig" template, which serves as the
     * welcome page for the application.
     *
     */
    #[Route("/", name: "app_home")]
    public function index()
    {
        return $this->render("home/welcome.html.twig", []);
    }
    /**
     * Subscribe or unsubscribe a user to/from a system's status updates.
     *
     * @param int $id The ID of the SystemStatus entity
     * @param Request $request The incoming request object
     * @param ManagerRegistry $registry The manager registry for accessing the database
     * @return Response The response after processing the subscription action
     * @throws NotFoundHttpException If the system status does not exist
     * @throws AccessDeniedException If the user is not logged in
     *
     * This method allows users to subscribe or unsubscribe from receiving status updates for a specific system.
     * It handles the subscription logic, checks the user's subscription status, and updates the subscription records.
     * The method also handles both subscription and unsubscription actions, and displays appropriate flash messages
     * to inform the user of the outcome.
     */
    #[Route("/home/{id}/subscribe", name: "app_home_subscribe")]
    public function subscribe($id, Request $request, ManagerRegistry $registry)
    {
        // Retrieve the SystemStatus entity from the database
        $system = $registry->getRepository(System::class)->find($id);
        if (!$system) {
            throw $this->createNotFoundException(
                "The system status does not exist"
            );
        }

        // Get the currently authenticated user
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException(
                "You must be logged in to subscribe"
            );
        }

        // Check if the user is already subscribed to the system
        $subscription = $registry
            ->getRepository(Subscription::class)
            ->findOneBy([
                "system" => $system,
                "user" => $user,
            ]);

        if ($request->getMethod() === "POST") {
            // If the subscription checkbox was unchecked, delete the subscription record
            if (!$request->request->get("subscribe")) {
                if ($subscription) {
                    $em = $registry->getManager();
                    $em->remove($subscription);
                    $em->flush();
                }
                // Add a flash message to indicate that the subscription was cancelled
                $this->addFlash(
                    "success",
                    "You have cancelled your subscription to " .
                        $system->getName()
                );
                return $this->redirectToRoute("app_allSubscription");
            }

            // If the subscription checkbox was checked, create a new subscription or update the existing one
            if (!$subscription) {
                $subscription = new Subscription();
                $subscription->setSystem($system);
                $subscription->setUser($user);
            }
            $subscription->setIsSubscribed(true);

            $em = $registry->getManager();
            $em->persist($subscription);
            $em->flush();

            // Add a flash message to indicate that the subscription was successful
            $this->addFlash(
                "success",
                "You have subscribed to " . $system->getName()
            );
            return $this->redirectToRoute("app_allSubscription");
        }
        $message =
            "You have subscribed to " .
            $system->getName() .
            "to Your subscription System site";
        // Render the subscription switch in the template
        // return $this->render('home/index.html.twig', [
        //     'statuses' => $status,
        //     'message' => $message,

        // ]);
        return $this->render("home/allSubscription.html.twig", [
            "system" => $em->getRepository(System::class)->findAll(),
        ]);
    }

     /**
     * Display all subscribed systems and their statuses for the currently authenticated user.
     *
     * This method fetches the subscribed systems for the authenticated user and retrieves their current statuses using
     * the `EventsController` to determine whether they are in an available, maintenance, or incident state. It then updates
     * the statuses of these systems accordingly. The list of subscribed systems and their statuses is rendered in the
     * template 'allSubscription.html.twig'.
     *
     */

     #[Route("/allSubscription", name: "app_allSubscription")]
     public function allSubscription(ManagerRegistry $managerRegistry,EventsController $eventsController): Response
     {
         // get the currently authenticated user
         $user = $this->getUser();
         // get the entity manager
         $em = $managerRegistry->getManager();
         //add status by system class instzancehof 
         $systems = $em->getRepository(System::class)->findAll();
        
         foreach ($systems as $system ) {
            
          $status =  $eventsController->getSystemStatus($system->getId(), $managerRegistry);
          $system->setStatus($status);
         }
         
         // get the list of SystemStatus entities that the user has subscribed to
         //$statuses = $em->getRepository(SystemStatus::class)->findSubscribedByUser($user);
 
         // render the template with the list of SystemStatus entities in allSubscription.html.twig
         return $this->render("home/allSubscription.html.twig", [
             "system" => $systems,
             "events"=> $em->getRepository(Events::class)->findAll(),
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
    
   
}
