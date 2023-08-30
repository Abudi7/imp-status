<?php

namespace App\Controller;

use App\Entity\Events;
use App\Entity\System;
use App\Entity\User;
use App\Entity\Subscription;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Template;
use App\Form\EventsType;
use App\Repository\TemplateRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\DateTime;

class EventsController extends AbstractController
{
    // Default route for the EventsController
    #[Route('/events', name: 'app_events')]
    public function index(): Response
    {
        return $this->render('events/index.html.twig', [
            'controller_name' => 'EventsController',
        ]);
    }
    // Create a new maintenance event
    #[Route("/events/{id}/new-Maintenance", name:"app_events_new_maintenance")]
    public function maintenance(Request $request, $id , ManagerRegistry $managerRegistry,MailerInterface $mailer): Response
    {
        // Get the authenticated user (admin)
        $admin = $this->getUser();
        
        // Load the System entity
        $entityManager = $managerRegistry->getManager();
        $system = $entityManager->getRepository(System::class)->find($id);

        // Create a new Events entity
        $event = new Events();
        $event->setSystem($system);
        $event->setCreator($admin);
     
        
        // Create the form for maintence event creation
        $form = $this->createFormBuilder($event)
        ->add('system', EntityType::class, [
            "class" => System::class,
            "choice_label" => "name",
            "placeholder" => "Select a system", // Optional placeholder text
            "required" => false, // Mark the field as not required
            "disabled" => true, // Set the field as disabled
        ])
        ->add('creator', EntityType::class,[
            "class" => User::class,
            "choice_label" => "email",
            "required" => false, // Mark the field as not required
            "disabled" => true, // Set the field as disabled

        ])
        ->add('created_at', DateTimeType::class, [
            'widget' => 'single_text',
            "required" => false, // Mark the field as not required
            "disabled" => true,
        ])
        ->add('type', ChoiceType::class, [
            'choices' => [
                'Maintenance' => 'maintenance',
            ],
            
        ])
        ->add('send_email', CheckboxType::class, [
            'label' => 'Send Email',
            'required' => false,
            'attr' => [
                'class' => 'custom-switch-input', // Add a class for the switch style
            ],       
        ])       
        ->add('start', DateTimeType::class, [
            'widget' => 'single_text',
            'html5' => true, // Enable HTML5 input type
            'attr' => [
                'min' => (new \DateTime())->format('Y-m-d\TH:i'), // Set min attribute to current date and time
            ],
        ])
        ->add('end', DateTimeType::class, [
            'widget' => 'single_text',
            'html5' => true, // Enable HTML5 input type
            'attr' => [
                'min' => (new \DateTime())->format('Y-m-d\TH:i'), // Set min attribute to current date and time
            ],
        ])
        ->add('info', TextType::class, [
            'attr' => [
                'class' => 'custom-css-class',
                'placeholder' => 'Enter information...',
            ],
        ])
        
        ->add('emailtemplate', EntityType::class, [
            'class' => Template::class,
            'choice_label' => 'title',
            'label' => 'Select Template Title',
            'query_builder' => function (TemplateRepository $repository) {
                return $repository->createQueryBuilder('t')
                    ->where('t.type = :maintenanceType')
                    ->setParameter('maintenanceType', 'maintenance')
                    ->orderBy('t.type', 'ASC');
            },
        ])
        ->add('subject', TextType::class,[
            'attr' => [
                'class' => 'custom-css-class',
                'placeholder' => 'Enter E-mail subject...',
            ],
        ])
        ->add('email', TextareaType::class, [
            'mapped' => true,
            'required' => false,
            'label' => 'Email Template',
            'attr' => ['rows' => 5, 'class' => 'template-textarea'], // Add a class for selecting the textarea with JS
        ]);
        $form = $form->getForm();
        // Handle form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Set the 'email' property based on the form data
            $event->setEmail($form->get('email')->getData());
            
             // Send email to subscribed users if send_email is checked
            if ($event->isSendEmail()) {
                $subscribedUsers = $system->getSubscriptions(); // Implement this method in your System entity

                
            foreach ($subscribedUsers as $subscription) {
                $user = $subscription->getUser(); // Assuming Subscription entity has a user relation
                $emailAddress = $user->getEmail();

                $email = (new Email())
                    ->from('your@example.com')
                    ->to($emailAddress)
                    ->subject('Maintenance Event Notification')
                    ->text($form->get('email')->getData());

                $mailer->send($email);
            }
            }
            // Persist the new event
            $entityManager->persist($event);
            $entityManager->flush();
            // Redirect to the system page or a confirmation page
            return $this->redirectToRoute("app_system_display", ["id" => $id]);
        }

        return $this->render("events/new-Maintenance.html.twig", [
            "form" => $form->createView(),
            "system" => $system,
        ]);
    }
    
    // Create a new incident event
    #[Route("/events/{id}/new-Incident", name:"app_events_new_incident")]
    public function incident(Request $request, $id , ManagerRegistry $managerRegistry): Response
    {
        // Get the authenticated user (admin)
        $admin = $this->getUser();
        
        // Load the System entity using Doctrine's Entity Manager
        $entityManager = $managerRegistry->getManager();
        $system = $entityManager->getRepository(System::class)->find($id);

        // Create a new Events entity for the incident event
        $event = new Events();
        $event->setSystem($system);
        $event->setCreator($admin);
        $event->setEnd(new \DateTime('9999-12-31')); // Set a future date as a placeholder for end date
        
        // Create the form for incident event creation
        $form = $this->createFormBuilder($event)
            ->add('system', EntityType::class, [
                "class" => System::class,
                "choice_label" => "name",
                "placeholder" => "Select a system", // Optional placeholder text
                "required" => false, // Mark the field as not required
                "disabled" => true, // Set the field as disabled
            ])
            ->add('creator', EntityType::class,[
                "class" => User::class,
                "choice_label" => "email",
                "required" => false, // Mark the field as not required
                "disabled" => true, // Set the field as disabled
    
            ])
            ->add('created_at', DateTimeType::class, [
                'widget' => 'single_text',
                "required" => false, // Mark the field as not required
                "disabled" => true,
            ])
            ->add('info', TextType::class, [
                'attr' => [
                    'class' => 'custom-css-class',
                    'placeholder' => 'Enter information...',
                ],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Incident' => 'incident',
                ],
            ])
            ->add('emailtemplate', EntityType::class, [
                'class' => Template::class,
                'choice_label' => 'title',
                'label' => 'Select Incident title',
                'query_builder' => function (TemplateRepository $repository) {
                    return $repository->createQueryBuilder('t')
                        ->where('t.type = :incidentType')
                        ->setParameter('incidentType', 'incident')
                        ->orderBy('t.type', 'ASC');
                },
            ])
            ->add('subject', TextType::class,[
                'attr' => [
                    'class' => 'custom-css-class',
                    'placeholder' => 'Enter E-mail subject...',
                ],
            ])
            ->add('send_email', CheckboxType::class, [
                'label' => 'Send Email',
                'required' => false,
                'attr' => [
                    'class' => 'custom-switch-input', // Add a class for the switch style
                ],       
            ])  
            ->add('email', TextareaType::class, [
                'mapped' => true,
                'required' => false,
                'label' => 'Email Template',
                'attr' => ['rows' => 5, 'class' => 'template-textarea'], // Customize the textarea appearance
            ]);
        $form = $form->getForm();

        // Handle form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Set the 'email' property based on the form data
            $event->setEmail($form->get('email')->getData());
            
            // Persist the new incident event to the database
            $entityManager->persist($event);
            $entityManager->flush();

            // Redirect to the system page or a confirmation page
            return $this->redirectToRoute("app_system_display", ["id" => $id]);
        }

        // Render the incident event creation form
        return $this->render("events/new-Incident.html.twig", [
            "form" => $form->createView(),
            "system" => $system,
        ]);
    }


   /**
     * Display the list of events associated with a system.
     *
     * @param int $systemId The ID of the system for which to retrieve events
     * @param ManagerRegistry $managerRegistry The Doctrine manager registry
     * @return Response
     */
    #[Route("/system/{systemId}/events", name:"app_events_system")]
    public function systemEvents($systemId, ManagerRegistry $managerRegistry): Response
    {
        // Get the Entity Manager from the registry
        $entityManager = $managerRegistry->getManager();

        // Load the System entity based on the provided systemId
        $system = $entityManager->getRepository(System::class)->find($systemId);

        // Check if the system exists, if not throw a 404 error
        if (!$system) {
            throw $this->createNotFoundException('System not found');
        }

        // Retrieve upcoming and previous maintenance events associated with the system
        $events = $entityManager->getRepository(Events::class)->findBySystem($system);

        // Separate upcoming and previous maintenance events
        $upcomingMaintenanceEvents = [];
        $previousMaintenanceEvents = [];

        $now = new \DateTime();
        foreach ($events as $event) {
            if ($event->getType() === 'maintenance') {
                if ($event->getStart() > $now) {
                    $upcomingMaintenanceEvents[] = $event;
                } else {
                    $previousMaintenanceEvents[] = $event;
                }
            }
        }

        // Render the events list view with the associated system, events, and maintenance events
        return $this->render('events/events-system.html.twig', [
            'system' => $system,
            'events' => $events,
            'upcomingMaintenanceEvents' => $upcomingMaintenanceEvents,
            'previousMaintenanceEvents' => $previousMaintenanceEvents,
        ]);
    }

   /**
     * Get a list of upcoming maintenance events for a given system.
     *
     * @param System $system The system entity
     * @param ManagerRegistry $managerRegistry The Doctrine manager registry
     * @return array An array of upcoming maintenance events
     */
    private function findFutureMaintenanceEvents(System $system, ManagerRegistry $managerRegistry): array
    {
        $entityManager = $managerRegistry->getManager();

        // Retrieve upcoming maintenance events associated with the system
        $events = $entityManager->getRepository(Events::class)->findFutureMaintenanceEvents($system);

        return $events;
    }



   // Edit an existing maintenance event
    #[Route("/events/{eventId}/edit-Maintenance", name:"app_events_edit_maintenance")]
    public function editMaintenance(Request $request, $eventId, ManagerRegistry $managerRegistry): Response
    {
        $entityManager = $managerRegistry->getManager();
        
        // Find the maintenance event by ID
        $event = $entityManager->getRepository(Events::class)->find($eventId);

        // Uncomment this section if you want to restrict editing to only maintenance events
        // if (!$event || $event->getType() !== 'maintenance') {
        //     throw $this->createNotFoundException('Maintenance event not found');
        // }

        // Create and handle the form for editing the maintenance event
        $form = $this->createForm(EventsType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Save the updated event to the database
            $entityManager->flush();

            // Redirect to the system display page
            return $this->redirectToRoute("app_system_display", ["id" => $event->getSystem()->getId()]);
        }

        // Render the edit maintenance event template
        return $this->render("events/edit-Maintenance.html.twig", [
            "form" => $form->createView(),
            "event" => $event,
            "system" => $event->getSystem(),
        ]);
    }

    /**
     * Resolve an incident event.
     *
     * @param int $id The ID of the incident event to resolve
     * @param ManagerRegistry $managerRegistry The Doctrine manager registry
     * @return Response
     */
     #[Route("/events/{id}/resolve-incident", name:"app_events_resolve_incident")]
    public function resolveIncident($id, ManagerRegistry $managerRegistry): Response
    {
        // Load the Event entity
        $entityManager = $managerRegistry->getManager();
        $event = $entityManager->getRepository(Events::class)->find($id);

        if (!$event) {
            return new JsonResponse(['success' => false, 'message' => 'Event not found'], 404);
        }
        
        // Mark the incident as resolved
        $event->setEnd(new \DateTime());
        // Update the event type to "Available"
        //$event->setType('available');

        $entityManager->flush();

        return $this->redirectToRoute('app_events_system', ['systemId' => $event->getSystem()->getId()]);
    }

//  /**
//  * Get the system status based on the events associated with it.
//  *
//  * @param int $id The ID of the system to retrieve the status for
//  * @param ManagerRegistry $managerRegistry The Doctrine manager registry
//  * 
//  */
// #[Route("/events/get-system-status/{id}", name:"get_system_status")]
// public function getSystemStatus($id, ManagerRegistry $managerRegistry): string
// {
//     $entityManager = $managerRegistry->getManager();
//     $system = $entityManager->getRepository(System::class)->find($id); // Assuming you have a System entity

//     if (!$system) {
//         return new Response(['status' => 'System not found'], 404);
//     }

//     // Retrieve the events associated with the system and order them by date in descending order
//     $events = $system->getEvents();
//     $latestEvent = null;

//     if ($events->count() > 0) {
//         $latestEvent = $events->last(); // Get the latest event
//     }

//     if ($latestEvent) {
//         // Check if the latest event is a maintenance event and if its start date is today or in the future
//         $currentDateTime = new \DateTime();
//         if ($latestEvent->getType() === 'maintenance' && $latestEvent->getStart() > $currentDateTime) {
//             $status = 'available'; // Set status to 'available' if maintenance event starts in the future
//         } else {
//             $status = $latestEvent->getType();
//         }
//     } else {
//         $status = 'available'; // Default status when no events are found
//     }

//     return $status;
// }

    /**
     * Get the system status based on the events associated with it.
     *
     * @param int $id The ID of the system to retrieve the status for
     * @param ManagerRegistry $managerRegistry The Doctrine manager registry
     * 
     */
    #[Route("/events/get-system-status/{id}", name:"get_system_status")]
    public function getSystemStatus($id, ManagerRegistry $managerRegistry): string
    {
        $entityManager = $managerRegistry->getManager();
        $system = $entityManager->getRepository(System::class)->find($id); // Assuming you have a System entity

        if (!$system) {
            return new JsonResponse(['status' => 'System not found'], 404);
        }

        // Retrieve the events associated with the system
        $events = $system->getEvents();

        $maintenanceActive = false;
        $incidentActive = false;

        foreach ($events as $event) {
            $now = new \DateTime();
            if ($event->getStart() <= $now && $event->getEnd() >= $now) {
                if($event->getType() === 'maintenance') {

                
                $maintenanceActive = true;
                } else {
                $incidentActive = true;
                }
            }
        }
        if ($maintenanceActive) {
            $status = 'maintenance';
        } elseif ($incidentActive) {
            $status = 'incident';
        } else {
            $status = 'available';
        }

        return  $status;
    }




    #[Route("/events/{id}/change-to-available", name:"app_events_change_to_available")]
    public function changeToAvailable($id, ManagerRegistry $managerRegistry): Response
    {
        // Get the authenticated user (admin)
        $admin = $this->getUser();
        
        // Load the Event entity
        $entityManager = $managerRegistry->getManager();
        $event = $entityManager->getRepository(Events::class)->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }

        
        // Check if the event is a maintenance event and has not started yet
        if ($event->getType() === 'maintenance' && !$event->getStart()) {
            $event->setStart(new \DateTime());
            $event->setEnd(new \DateTime());
        } else {
            // Only update the end date for maintenance events that have started
            if ($event->getType() === 'maintenance') {
                $event->setEnd(new \DateTime());
            }
        }
        
        // Persist the updated event
        $entityManager->flush();

        return $this->redirectToRoute('app_system_display', ['id' => $event->getSystem()->getId()]);
    }



      /**
     * Edit an existing event record.
     *
     * @param Request $request The HTTP request object
     * @param int $id The ID of the event to edit
     * @param ManagerRegistry $managerRegistry The Doctrine manager registry
     * @return Response
     */

     #[Route("/events/{id}/edit", name:"app_events_edit")]
     public function edit(Request $request, $id, ManagerRegistry $managerRegistry): Response
     {
         $entityManager = $managerRegistry->getManager();
         $event = $entityManager->getRepository(Events::class)->find($id);
         if (!$event) {
             throw $this->createNotFoundException('Event not found');
         }
     
         // Create the form using the EventsType form type and pre-populate it with the event data
         $form = $this->createForm(EventsType::class, $event);
     
         $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
             // Save the changes to the event
             $entityManager->flush();
          
             return $this->redirectToRoute('app_events_system', ['systemId' => $event->getSystem()->getId()]);
         }
     
         return $this->render('events/edit-events.html.twig', [
             'form' => $form->createView(),
             'event' => $event,
         ]);
     }
     /**
     * Get future maintenance events for a system.
     *
     * @param int $systemId The ID of the system
     * @param ManagerRegistry $managerRegistry The Doctrine manager registry
     * @return array
     */
    #[Route("/system/{systemId}/future-maintenance", name: "app_system_future_maintenance")]
    public function futureMaintenance($systemId, ManagerRegistry $managerRegistry): Response
    {
        $entityManager = $managerRegistry->getManager();
        $system = $entityManager->getRepository(System::class)->find($systemId);

        if (!$system) {
            return new JsonResponse(['message' => 'System not found'], 404);
        }

        // Retrieve future maintenance events associated with the system
        $currentDateTime = new \DateTime();
        $futureMaintenanceEvents = $entityManager->getRepository(Events::class)->findFutureMaintenanceEvents($system, $currentDateTime);

        // Return the future maintenance events as a JSON response
        return $this->json($futureMaintenanceEvents);
    }

}
