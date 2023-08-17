<?php

namespace App\Controller;

use App\Entity\Events;
use App\Entity\System;
use App\Entity\Template;
use App\Form\EventsType;
use App\Repository\TemplateRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EventsController extends AbstractController
{
    #[Route('/events', name: 'app_events')]
    public function index(): Response
    {
        return $this->render('events/index.html.twig', [
            'controller_name' => 'EventsController',
        ]);
    }

    #[Route("/events/{id}/new-Maintenance", name:"app_events_new_maintenance")]
    public function maintenance(Request $request, $id , ManagerRegistry $managerRegistry): Response
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
        ->add('type', ChoiceType::class, [
            'choices' => [
                'Maintenance' => 'maintenance',
            ],
        ])
        ->add('start', DateTimeType::class, [
            'widget' => 'single_text',
        ])
        ->add('end', DateTimeType::class, [
            'widget' => 'single_text',
        ])
        ->add('emailtemplate', EntityType::class, [
            'class' => Template::class,
            'choice_label' => 'subject',
            'label' => 'Select Template', // Adjust label as needed
        ])
        ->add('email', TextareaType::class, [
            'mapped' => false,
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
    
    #[Route("/events/{id}/new-Incident", name:"app_events_new_incident")]
    public function incident(Request $request, $id , ManagerRegistry $managerRegistry): Response
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
        $event->setEnd(new \DateTime('9999-12-31')); // Set a future date as a placeholder
        
        // Create the form for maintence event creation
        $form = $this->createFormBuilder($event)
        ->add('system', EntityType::class, [
            "class" => System::class,
            "choice_label" => "name",
            "placeholder" => "Select a system", // Optional placeholder text
            "required" => false, // Mark the field as not required
            "disabled" => true, // Set the field as disabled
        ])
        ->add('type', ChoiceType::class, [
            'choices' => [
                'Incident' => 'incident',
            ],
        ])
        // ->add('end', DateType::class, [
        //     'widget' => 'single_text',
        // ])
        ->add('emailtemplate', EntityType::class, [
            'class' => Template::class,
            'choice_label' => 'subject',
            'label' => 'Select Template', // Adjust label as needed
        ])
        ->add('email', TextareaType::class, [
            'mapped' => false,
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
            // Persist the new event
            $entityManager->persist($event);
            $entityManager->flush();

            // Redirect to the system page or a confirmation page
            return $this->redirectToRoute("app_system_display", ["id" => $id]);
        }

        return $this->render("events/new-Incident.html.twig", [
            "form" => $form->createView(),
            "system" => $system,
        ]);
    }

    /**
     * display system events liste 
     */
    #[Route("/system/{systemId}/events", name:"app_events_system")]
    public function systemEvents($systemId, ManagerRegistry $managerRegistry): Response
    {
        $entityManager = $managerRegistry->getManager();
        $system = $entityManager->getRepository(System::class)->find($systemId);

        if (!$system) {
            throw $this->createNotFoundException('System not found');
        }

        $events = $entityManager->getRepository(Events::class)->findBy(['system' => $system]);

        return $this->render('events/events-system.html.twig', [
            'system' => $system,
            'events' => $events,
        ]);
    }


    /**
     * Edit Record
     */

     #[Route("/events/{id}/edit", name:"app_events_edit")]
     public function edit(Request $request, $id, ManagerRegistry $managerRegistry): Response
     {
         $entityManager = $managerRegistry->getManager();
         $event = $entityManager->getRepository(Events::class)->find($id);
         if (!$event) {
             throw $this->createNotFoundException('Event not found');
         }
     
         $form = $this->createForm(EventsType::class, $event);
     
         $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
             $entityManager->flush();
             // You can add a success flash message here if needed
             return $this->redirectToRoute('app_events_system', ['systemId' => $event->getSystem()->getId()]);
         }
     
         return $this->render('events/edit-events.html.twig', [
             'form' => $form->createView(),
             'event' => $event,
         ]);
     }

     /**
      * Resolve Incident
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
        $event->setType('available');

        $entityManager->flush();

        return $this->redirectToRoute('app_events_system', ['systemId' => $event->getSystem()->getId()]);
    }

    #[Route("/events/get-system-status/{id}", name:"get_system_status")]
    public function getSystemStatus($id, ManagerRegistry $managerRegistry): JsonResponse
    {
        $entityManager = $managerRegistry->getManager();
        $events = $entityManager->getRepository(Events::class)->find($id);

        if (!$events) {
            return new JsonResponse(['status' => 'System not found'], 404);
        }

        // Implement logic to determine the system status (maintenance, incident, available)
        $status = $events->getType(); 

        return new JsonResponse(['status' => $status]);
    }



}
