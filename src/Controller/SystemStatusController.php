<?php

namespace App\Controller;

use App\Entity\Status;
use App\Entity\SystemStatus;
use App\Entity\User;
use App\Form\SystemStatusType;
use App\Repository\SubscriptionRepository;
use App\Repository\SystemStatusRepository;
use DateTime;
use DateTimeImmutable;
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
     * This controller is responsible for managing system statuses.
     * It provides actions for viewing, adding, editing, and deleting system statuses.
     * It also includes methods for sending maintenance and incident notifications by email.
     */

    /**
     * View all systems statuses at the moment.
     * This action retrieves all system statuses from the repository and renders the index view.
     */

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
     * Adding the state of a new system to the database,
     * storing it and displaying it to the user in the main interface.
     * This action handles the form submission for adding a new system status.
     * It creates a new SystemStatus instance, binds the form data, and persists it to the database.
     */

     #[Route("/system_status/new", name: "app_system_status_new")]
     public function new(Request $request,ManagerRegistry $entityManager,TokenStorageInterface $tokenStorage) {
         // Create new instances of SystemStatus and User entities
         $status = new SystemStatus();
         $user = new User();
     
         // Create the form with the "Available" option passed to the form type
         $form = $this->createForm(SystemStatusType::class, $status, [
             "Available" => $entityManager
                 ->getRepository(Status::class)
                 ->findOneBy(["name" => "Available"]),
         ]);
        // Handle form submission
         $form->handleRequest($request);
     
         if ($form->isSubmitted() && $form->isValid()) {
             // Get the data from the form
             $status = $form->getData();
             $entityManager = $entityManager->getManager();
     
             // Get the logged-in user
             $user = $tokenStorage->getToken()->getUser();
     
             // Set the Responsible Person field on the SystemStatus entity
             $status->setResponsible_Person($user->getUserIdentifier());
     
             // Persist and flush the SystemStatus entity
             $entityManager->persist($status);
             $entityManager->flush();
            // Redirect to the system status index page
             return $this->redirectToRoute("app_system_status");
         }
         // Render the new view with the form
         return $this->render("system_status/new.html.twig", [
             "form" => $form->createView(),
         ]);
     }
     
    /**
     * =======================================
     * Show one system status by ID
     * This action retrieves a specific system status by its ID and renders the show view.
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
     * This action handles the form submission for editing a system status.
     * It retrieves the existing system status, binds the form data, and updates the database record.
     * ========================================================================
     */
    
    #[Route("/system_status/{id}/edit", name: "app_system_status_edit")]
    public function edit(Request $request, SystemStatus $status, ManagerRegistry $entityManager, MailerInterface $mailer, $id)
    {
        // Retrieve the actual data from the database
        $form = $this->createForm(SystemStatusType::class,$status,[
            // Pass the "Available" option to the form type
            "Available" => $entityManager
                ->getRepository(Status::class)
                ->findOneBy(
                    ['name' =>'Available']
                ),
        ]);
            
        $form->handleRequest($request);
        $showMaintenanceFields = $status->getStatus()->getName() === 'Maintenance';
    
        if ($form->isSubmitted() && $form->isValid()) {
            $status->setUpdatedAt(new \DateTime());
    
            if ($status->getStatus()->getName() === 'Maintenance') {
                // Set maintenance start and end based on the retrieved status entity
                $maintenanceTimes = $entityManager
                ->getRepository(SystemStatus::class)
                ->find($status->getId());
                $status->setMaintenanceStart($maintenanceTimes->getMaintenanceStart());
                $status->setMaintenanceEnd($maintenanceTimes->getMaintenanceEnd());
            } else {
                // Reset maintenance start and end if status is not "Maintenance"
                $status->setMaintenanceStart(null);
                $status->setMaintenanceEnd(null);
            }
    
            $entityManager = $entityManager->getManager();
            $entityManager->persist($status);
            $entityManager->flush();
    
            // Check maintenance start and end conditions for redirecting
            if ($status->getStatus()->getName() === 'Maintenance' && $status->getMaintenanceStart() == null){
                return $this->redirectToRoute('app_system_status_edit',['id'=>$id]);
            } elseif($status->getStatus()->getName() === 'Maintenance' && $status->getMaintenanceEnd() == null) {
                return $this->redirectToRoute('app_system_status_edit',['id'=>$id]);
            }
            
            // Redirect to the maintenance route if start and end are set
            if ($status->getStatus()->getName() === 'Maintenance' && $status->getMaintenanceStart() != null && $status->getMaintenanceEnd() != null ) {
                return $this->redirectToRoute('app_system_status_maintenance', ['id' => $id]);
            }
    
            // Redirect to the system status route if not in maintenance mode
            return $this->redirectToRoute('app_system_status');
        }
    
        return $this->render('system_status/edit.html.twig', [
            'systemStatus' => $status,
            'form' => $form->createView(),
            'showMaintenanceFields' => $showMaintenanceFields,
        ]);
    }
    
    /**
     * ========================================================================
     * Maintenance Notification by Email
     * This method is responsible for sending maintenance notification emails
     * to the users specified in the configuration.
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
    
        if ($systemStatus->getStatus() == 'Maintenance') {
            foreach ($subscribedUsers as $subscription) {
                $user = $subscription->getUSER();
                $to = $user->getEmail();
                
                // Maintenance Email 
                $subject = $systemStatus->getSystem() . ' Maintenance Notification';
                $message = 'Dear ' . $user->getUsername() . ',' . "\r\n\n\n" .
                    'The system ' . $systemStatus->getSystem() . ' will be undergoing maintenance.' . "\r\n\n" .
                    'Maintenance Details:' . "\r\n\n" . "\t" .
                    '- Start Time: ' . $systemStatus->getMaintenanceStart()->format('M d, Y H:i') . "\r\n\t" .
                    '- End   Time: ' . $systemStatus->getMaintenanceEnd()->format('M d, Y H:i') . "\r\n\n\n" .
                    'During this scheduled maintenance, our technical team will be working diligently to improve the performance and reliability of our systems.' . "\r\n\n" .
                    'While we strive to minimize any disruption, you may experience temporary service interruptions or degraded performance of certain features during the specified maintenance window.' . "\r\n\n" .
                    'We apologize for any inconvenience this may cause and assure you that our team will be working diligently to complete the maintenance as quickly as possible.' . "\r\n\n" .
                    'We highly recommend that you plan your work accordingly and save any important data or progress before the maintenance window begins.' . "\r\n\n" .
                    'Rest assured, once the maintenance is complete, you will be able to resume using our services without any issues.' . "\r\n\n" .
                    'Our team will keep you updated throughout the process, and we will notify you promptly if there are any changes or updates to the maintenance schedule.' . "\r\n\n" .
                    'If you have any questions or concerns regarding this maintenance, please don\'t hesitate to reach out to our support team at [it-helpdesk@anton-paar.com]. We will be happy to assist you.' . "\r\n\n" .
                    'Thank you for your understanding and cooperation as we work towards enhancing our services for a better user experience.' . "\r\n\n" .
                    'Best regards,' . "\r\n" .
                    'IT Helpdesk';
                
                $headers = 'it-helpdesk@anton-paar.com';
    
                $email = (new TemplatedEmail())
                    ->from($headers)
                    ->to($to)
                    ->subject($subject)
                    ->text($message)
                    ->context([
                        'user' => $user,
                    ]);
                // Send maintenance email notification to subscribed users
                $mailer->send($email);
                #$date = new \DateTime('today', new \DateTimeZone('Europe/Vienna'));
                // Log the sent email to a file
                $this->logEmail($to, 'Maintenance',$subject, $headers);# , $date->format('y-m-d h:i:s'));
                // Add a success flash message since the email sending process was successful
                $this->addFlash('success', 'Maintenance notifications sent successfully.');
                if (mail($to, $subject, $message, $headers)) {
                    // Display a success flash message if the email is sent successfully, otherwise display an info flash message
                   $this->addFlash('success', 'Maintenance notifications sent successfully.');
                } else {
                    $this->addFlash('info', 'No maintenance notification is required.');
                }
               
            }
            
        }
    
        return $this->redirectToRoute('app_system_status');
    }
    

   /**
     * ========================================================================
     * Incident Notification by Email
     * This method is responsible for sending incident notification emails
     * to the users specified in the configuration.
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
                #$date = new \DateTime('today', new \DateTimeZone('Europe/Vienna'));
                // $this->logEmail($to, $subject, $headers, $date->format('Y-m-d H:i:s'));
                $this->logEmail($to, "Incident", $subject, $headers);

                if (mail($to, $subject, $message, $headers)) {
                    $this->addFlash('success', 'Incident notifications sent successfully.');
                } else {
                    $this->addFlash('info', 'No Incident notification is required.');
                }
               
            }
            
        }
    
        return $this->redirectToRoute('app_system_status');
    }
      /**
     * Logs the sent email to a file.
     *
     * @param string $to      The recipient of the email
     * @param string $type    The type of email
     * @param string $subject The subject of the email
     * @param string $headers The headers of the email
     */
    private function logEmail($to, $type, $subject, $headers)
    {
        // Get the current request to access the RequestStack
        //$request = $this->container->get(RequestStack::class)->getCurrentRequest();

        // Get the log file path
        //$logFilePath = $request->server->get('DOCUMENT_ROOT') . 'C:/xampp/mailoutput/email_log.txt';
        $logFilePath = 'C:' . DIRECTORY_SEPARATOR . 'xampp' . DIRECTORY_SEPARATOR . 'mailoutput' . DIRECTORY_SEPARATOR . 'email_log.txt';

        $timestamp = new DateTime('now', new \DateTimeZone('Europe/Vienna'));

        // Format the log entry
        $logEntry = sprintf(
            "%s %s To: %s Subject: %s Headers: %s \n",
            $timestamp->format('Y-m-d H:i:s'),
            strtoupper($type),
            $to,
            $subject,
            $headers 
        );
        

        // Append the log entry to the log file
        file_put_contents($logFilePath, $logEntry, FILE_APPEND);
    }

     
   /**
     * ========================================================================
     * Delete System Status 
     * This action handles the deletion of a system status by its ID.
     * It retrieves the system status, removes it from the database, and redirects to the index page.
     * ========================================================================
     */

    #[Route("/system_status/{id}/delete", name: "app_system_status_delete")]
    public function delete(
        $id,
        ManagerRegistry $entityManager,
        SystemStatusRepository $systemStatusRepository
    ) {
        $em = $entityManager->getManager();
        $deleteSystem = $systemStatusRepository->find($id);
    
        if (!$deleteSystem) {
            throw $this->createNotFoundException('The record does not exist');
        }
    
        // Retrieve associated subscriptions
        $subscriptions = $deleteSystem->getSubscriptions();
    
        // Remove associated subscriptions first
        foreach ($subscriptions as $subscription) {
            $em->remove($subscription);
        }
    
        // Remove the system status record
        $em->remove($deleteSystem);
        $em->flush();
    
        $this->addFlash("success", "The system has been deleted");
    
        return $this->redirectToRoute("app_system_status");
    }
}


