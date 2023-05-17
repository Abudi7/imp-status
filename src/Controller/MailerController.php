<?php

namespace App\Controller;

use App\Repository\SystemStatusRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    #[Route('/mailer', name: 'app_mailer')]
public function sendEmail(MailerInterface $mailer, UserRepository $userRepository,SystemStatusRepository $systemStatusRepository): Response
{
    $system = $systemStatusRepository->findAll();
    // Retrieve subscribed users
    $subscribedUsers = $userRepository->findSubscribedUsers();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        foreach ($system as $systemStatus) {
            $maintenanceStart = $systemStatus->getCreatedAt()->format('m-d H:i');
            $maintenanceEnd = $systemStatus->getUpdatedAt()->format('m-d H:i');
            
            foreach ($subscribedUsers as $user) {
                $to = $user->getEmail();
                $subject = $systemStatus->getSystem() . ' Maintenance Notification';
                $message = 'Dear ' . $user->getUsername() . '!\r\n' .
                    'The system will be under maintenance from ' . $maintenanceStart . ' to ' . $maintenanceEnd . '.<br>' .
                    'We apologize for the inconvenience.';
                $headers = 'SystemStatus@anton-paar.com';
        
                $email = (new TemplatedEmail())
                    ->from($headers)
                    ->to($to)
                    ->subject($subject)
                    ->html($message)
                    ->context([
                        'user' => $user,
                    ]);
                
                $mailer->send($email);
            }
        }
        
        if (mail($to, $subject, $message, $headers)) {
            echo 'Email sent successfully.';
        } else {
            echo 'Failed to send email.';
        }
    }

    return $this->render('mailer/mailer.html.twig', [
        
    ]);
}

}