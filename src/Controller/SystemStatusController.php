<?php

namespace App\Controller;

use App\Entity\Status;
use App\Entity\SystemStatus;
use App\Entity\User;
use App\Form\SystemStatusType;
use App\Repository\SubscriptionRepository;
use App\Repository\SystemStatusRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
//use Symfony\Component\HttpFoundation\Response;
class SystemStatusController extends AbstractController
{
    /**
     * =======================================
     * View all systems statuses at the moment
     * =======================================
     */
    #[Route("/system_status", name: "app_system_status")]
    public function index(SystemStatusRepository $systemStatusRepository)
    {
        $statuses = $systemStatusRepository->findAll();
        return $this->render("system_status/index.html.twig", [
            "statuses" => $statuses,
        ]);
    }

    /**
     * =================================================================
     * Method new
     * Adding the state of a new system to the database,
     * storing it and displaying it to the user in the main interface.
     * =================================================================
     */

    #[Route("/system_status/new", name: "app_system_status_new")]
    public function new(
        Request $request,
        ManagerRegistry $entityManager,
        TokenStorageInterface $tokenStorage
    ) {
        $status = new SystemStatus();
        $user = new User();

        $form = $this->createForm(SystemStatusType::class, $status, [
            "Available" => $entityManager
                ->getRepository(Status::class)
                ->findOneBy(["name" => "Available"]),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $status = $form->getData();
            $entityManager = $entityManager->getManager();
            // Get the logged-in user
            $user = $tokenStorage->getToken()->getUser();

            // Set the Responsible Person field on the SystemStatus entity
            $status->setResponsible_Person($user->getUserIdentifier());

            $entityManager->persist($status);
            $entityManager->flush();

            return $this->redirectToRoute("app_system_status");
        }

        return $this->render("system_status/new.html.twig", [
            "form" => $form->createView(),
        ]);
    }
    /**
     * =======================================
     * Show one system status by ID
     * =======================================
     */

    #[Route("/system_status/{id}", name: "app_system_status_show")]
    public function show(SystemStatus $status)
    {
        return $this->render("system_status/show.html.twig", [
            "status" => $status,
        ]);
    }

    /**
     * ========================================================================
     * Modify the state of the stored system in the database,
     * store it again, and display it to the user in the main interface.
     * ========================================================================
     */
    #[Route("/system_status/{id}/edit", name: "app_system_status_edit")]
    public function edit(
        Request $request,
        SystemStatus $status,
        ManagerRegistry $entityManager
    ) {
        $form = $this->createForm(SystemStatusType::class, $status, [
            "Available" => $entityManager
                ->getRepository(Status::class)
                ->findOneBy(["name" => "Available"]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $entityManager->getManager();
            $status->setUpdatedAt(new \DateTime());
            $entityManager->persist($status);
            $entityManager->flush();

            return $this->redirectToRoute('app_system_status');
        }

        return $this->render('system_status/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * ========================================================================
     * Maintenance Notification by Email
     * ========================================================================
     */
    #[Route("/system_status/{id}/maintenance", name: "app_system_status_maintenance")]
    public function maintenance(
        $id,
        MailerInterface $mailer,
        SubscriptionRepository $subscriptionRepository,
        SystemStatusRepository $systemStatusRepository,
    ) {
        $systemStatus = $systemStatusRepository->find($id);
    
        // Retrieve subscribed users
        //$Users = $userRepository->findSubscribedUsers();
        $subscribedUsers =$subscriptionRepository->findSubscribedUsers($id);
    
        if ($systemStatus->getStatus() == 'Update' || $systemStatus->getStatus() == 'In progress') {
            foreach ($subscribedUsers as $subscription) {
                $user = $subscription->getUSER();
                $to = $user->getEmail();
              
                $subject = $systemStatus->getSystem() . ' Maintenance Notification';
                $message = 'Dear ' . $user->getUsername() . ',' . "\r\n\n\n" .
                    'The system'  . $systemStatus->getSystem() . ' will be undergoing maintenance.' . "\r\n\n" .
                    'Maintenance Details:' . "\r\n\n" . "\t".
                    '- Start Time: ' . $systemStatus->getCreatedAt()->format('M d, Y H:i') . "\r\n\t" .
                    '- End Time: ' . $systemStatus->getUpdatedAt()->format('M d, Y H:i') . "\r\n\n\n" .
                    'We apologize for any inconvenience caused during this maintenance period.' . "\r\n\n" .
                    'Thank you for your understanding.'."\r\n\n".'Best regards,'."\r\n".'IT Helpdesck';
                $headers = 'SystemStatus@anton-paar.com';
    
                $email = (new TemplatedEmail())
                    ->from($headers)
                    ->to($to)
                    ->subject($subject)
                    ->text($message)
                    ->context([
                        'user' => $user,
                    ]);
    
                $mailer->send($email);
                if (mail($to, $subject, $message, $headers)) {
                    $this->addFlash('success', 'Maintenance notifications sent successfully.');
                } else {
                    $this->addFlash('info', 'No maintenance notification is required.');
                }
               
            }
            
        }
    
        return $this->redirectToRoute('app_system_status_show', ['id' => $id]);
    }
    
   /**
     * ========================================================================
     * Incident Notification by Email
     * ========================================================================
     */
    #[Route("/system_status/{id}/incident", name: "app_system_status_incident")]
    public function incident(
        $id,
        MailerInterface $mailer,
        SubscriptionRepository $subscriptionRepository,
        SystemStatusRepository $systemStatusRepository,
    ) {
        $systemStatus = $systemStatusRepository->find($id);
    
        // Retrieve the users subscribed to the system using Doctrine SQL query.
        //$Users = $userRepository->findSubscribedUsers();
        $subscribedUsers =$subscriptionRepository->findSubscribedUsers($id);
    
        if ($systemStatus->getStatus() == 'Down') {
            foreach ($subscribedUsers as $subscription) {
                $user = $subscription->getUSER();
                $to = $user->getEmail();
              
                $subject = $systemStatus->getSystem() . ' Incident Notification';
                $message = 'Dear ' . $user->getUsername() . ',' . "\r\n\n" .
                    'The system ' . $systemStatus->getSystem() . ' will be under Incident.' . "\t\r\n\n" .
                    'Incident Details:' . "\r\n" .
                    'Start Time: ' . $systemStatus->getUpdatedAt()->format('M d, Y H:i') . "\r\n\n\n" .
                    'We apologize for the inconvenience caused during this incident. Our team is actively working to resolve the issue and restore normal operations as soon as possible.' . "\r\n" .
                    'Thank you for your understanding and patience. If you have any questions or need further assistance, please feel free to contact our support team.'. "\r\n\n" .'Best regards,'. "\r\n\n" .'IT Helpdesck';
                $headers = 'SystemStatus@anton-paar.com';
    
                $email = (new TemplatedEmail())
                    ->from($headers)
                    ->to($to)
                    ->subject($subject)
                    ->text($message)
                    ->context([
                        'user' => $user,
                    ]);
    
                $mailer->send($email);
                if (mail($to, $subject, $message, $headers)) {
                    $this->addFlash('success', 'Incident notifications sent successfully.');
                } else {
                    $this->addFlash('info', 'No Incident notification is required.');
                }
               
            }
            
        }
    
        return $this->redirectToRoute('app_system_status');
    }



    #[Route("/system_status/{id}/delete", name: "app_system_status_delete")]
    public function delete(
        $id,
        ManagerRegistry $entityManager,
        SystemStatusRepository $systemStatusRepository
    ) {
        // Get the repository for the entity you wish to delete
        $em = $entityManager->getManager();
        //$repository = $entityManager->getRepository(SystemStatus::class);

        // Find the record you wish to delete by ID
        $deleteSystem = $systemStatusRepository->find($id);

        /* Check if the record was found
        if (!$status) {
            throw $this->createNotFoundException('The record does not exist');
        }*/

        // Remove the record from the entity manager
        $em->remove($deleteSystem);
        $em->flush();

        //create Message
        $this->addFlash("sucsses", "The system has been deleted");

        // Redirect to the page that displays the remaining records
        return $this->redirectToRoute("app_system_status");
    }
}