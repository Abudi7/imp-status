<?php

namespace App\Controller;

use App\Entity\System;
use App\Form\SystemType;
use App\Repository\SystemRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SystemController extends AbstractController
{
    #[Route("/system", name: "app_system")]
    public function index(SystemRepository $systemRepository): Response
    {
        // Retrieve all systems from the database
        $systems = $systemRepository->findAll();
    
        // Render the systems index view and pass the systems data
        return $this->render("system/index.html.twig", [
            "systems" => $systems,
        ]);
    }
    
    #[Route("/system/new", name: "app_system_new")]
    public function new(Request $request, ManagerRegistry $managerRegistry)
    {
        // Create a new instance of the System entity
        $system = new System();
    
        // Create the form for adding a new system
        $form = $this->createForm(SystemType::class);
    
        // Handle the form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Get the submitted form data
            $system = $form->getData();
    
            // Save the new system to the database
            $em = $managerRegistry->getManager();
            $em->persist($system);
            $em->flush();
    
            // Redirect to the system status page
            return $this->redirectToRoute("app_system_status");
        }
    
        // Render the new system form view
        return $this->render("system/new.html.twig", [
            "form" => $form->createView(),
        ]);
    }
}
