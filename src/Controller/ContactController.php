<?php

// src/Controller/ContactController.php

namespace App\Controller;

use App\Form\ContactType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Attachment;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

class ContactController extends AbstractController
{
    /**
     * Handle the contact form submission and send an email.
     *
     */
    #[Route("/contact", name: "app_contact")]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
       
        // Create a form using ContactType
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            try{
                $transport = Transport::fromDsn($_ENV['MAILER_DSN']);
                $mailer = new Mailer($transport);

                $email = (new Email())
                    ->from($_ENV['MAILER_FROM'])
                    ->to('abdulrhman.al@anton-paar.com')
                    ->subject('Time for Symfony Mailer!')
                    ->text($formData['message']);
                    
                
                $mailer->send($email);
    
            // // Attach the uploaded image as an inline image
            // if ($formData['attachment']) {
            //     $imageData = fopen($formData['attachment']->getPathname(), 'r');
            //     $imageName = $formData['attachment']->getClientOriginalName();
            //     $imageMimeType = $formData['attachment']->getMimeType();
            //     $imagePart = (new DataPart($imageData, $imageName, $imageMimeType))->asInline();
            //     $email->addPart($imagePart);
            // }
    
            // Send the email using the Mailer service
         
            }catch(Exception $e){
               
                echo $e->getMessage();
            }
                     
            
            
          
            // Add a success flash message
            $this->addFlash('success', 'Your message has been sent.');
    
            // Redirect to the home page
            return $this->redirectToRoute('app_contact');
        }
    
        // Render the contact form template
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
