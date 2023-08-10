<?php

namespace App\Controller;

use App\Entity\Events;
use App\Entity\System;
use App\Entity\Template;
use App\Form\EventsType;
use App\Repository\TemplateRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    #[Route("/events/{id}/new", name:"app_events_new")]
    public function new(Request $request, $id , ManagerRegistry $managerRegistry): Response
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

        
        // Create the form for event creation
        $form = $this->createForm(EventsType::class, $event);

        // Handle form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the new event
            $entityManager->persist($event);
            $entityManager->flush();

            // Redirect to the system page or a confirmation page
            return $this->redirectToRoute("app_system_display", ["id" => $id]);
        }

        return $this->render("events/new.html.twig", [
            "form" => $form->createView(),
            "system" => $system,
        ]);
    }

    #[Route("/events/get-template-content/{id}", name: "get_template_content")]
    public function getTemplateContent($id, TemplateRepository $templateRepository): JsonResponse
    {
        $template = $templateRepository->find($id);

        if (!$template) {
            return new JsonResponse(['content' => 'Template not found'], 404);
        }

        return new JsonResponse(['content' => $template->getTemplate()]);
    }


}
