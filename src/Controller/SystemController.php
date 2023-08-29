<?php

namespace App\Controller;

use App\Controller\EventsController;
use App\Entity\System;
use App\Form\SystemType;
use App\Repository\SystemRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SystemController extends AbstractController
{
     /**
     * Build a private method to change the value in the database.
     *
     * @param integer $id
     * @param ManagerRegistry $managerRegistry
     * @param boolean $active
     * @return array
     */
    private function updateIsActive($id, ManagerRegistry $managerRegistry, $active): array
    {
        $em = $managerRegistry->getManager();
        $system = $em->getRepository(System::class)->find($id);

        if (!$system) {
            return ['success' => false, 'message' => 'System status not found.'];
        }

        $previousIsDeactive = $system->isActive();
        
        if ($previousIsDeactive !== $active) {
            $system->setActive($active);
            $em->persist($system);
            $em->flush();

            $message = $active
                ? 'System status has been deactivated.'
                : 'System status has been reactivated.';
                
            return ['success' => true, 'message' => $message];
        } else {
            $message = $active
                ? 'System status is already deactivated.'
                : 'System status is already active.';
                
            return ['success' => false, 'message' => $message];
        }
    }

    #[Route("/system", name: "app_system")]
    public function index(SystemRepository $systemRepository, Request $request, ManagerRegistry $managerRegistry, EventsController $eventsController): Response
    {
        // Retrieve all systems from the database
        $systems = $systemRepository->findAll();
        // Check if the request contains the 'status_id' and 'isdeactive' parameters
        $systemId = $request->get('system_id');
        $active = $request->get('active');
        
        if ($systemId !== null && $active !== null) {
            // Call the updateIsDeactive() method with the provided parameters
            $updateResult = $this->updateIsActive($systemId, $managerRegistry, $active);
            
            // Handle the update result, e.g., show a flash message
            if ($updateResult['success']) {
                $this->addFlash('success', $updateResult['message']);
            } else {
                $this->addFlash('error', $updateResult['message']);
            }
        }
        foreach ($systems as $system ) {
           
            $status =  $eventsController->getSystemStatus($system->getId(), $managerRegistry);
            $system->setStatus($status);
           }
        // Render the systems index view and pass the systems data
        return $this->render("system/index.html.twig", [
            "systems" => $systems,
        ]);
    }

    /**
     * Add new System 
     */
    
     #[Route("/system/new", name: "app_system_new")]
     public function new(Request $request, ManagerRegistry $managerRegistry, TokenStorageInterface $tokenStorage)
     {
         // Get the authenticated user
         $user = $tokenStorage->getToken()->getUser();
 
         // Create a new instance of the System entity
         $system = new System();
 
         // Set the creator (user) for the system
         $system->setCreator($user);
 
         // Create the form for adding a new system
         $form = $this->createForm(SystemType::class, $system);
 
         // Handle the form submission
         $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
             // Save the new system to the database
             $em = $managerRegistry->getManager();
             $em->persist($system);
             $em->flush();
 
             // Redirect to the system status page
             return $this->redirectToRoute("app_system");
         }
 
         // Render the new system form view
         return $this->render("system/new.html.twig", [
             "form" => $form->createView(),
         ]);
     }
     /**
     * Edit or Update System 
     */
    #[Route("/system/{id}/edit", name: "app_system_edit")]
    public function edit(Request $request, System $system, ManagerRegistry $managerRegistry)
    {
        // Create the form for editing the system
        $form = $this->createForm(SystemType::class, $system);

        // Handle the form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Save the updated system to the database
            $em = $managerRegistry->getManager();
            $em->flush();

            // Redirect to the system status page or other appropriate page
            return $this->redirectToRoute("app_system");
        }

        // Render the edit system form view
        return $this->render("system/edit.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    /**
     * Disply {id} System 
     */
    #[Route("/system/{id}/display" ,name: "app_system_display" )]
    public function display(System $system): Response {
        // Render the "display.html.twig" template and pass the system data to it
        return $this->render("system/display.html.twig", [
            "displays" => $system
        ]);
    }

    /**
     * Delete {id} System 
     */
    #[Route("/system/{id}/delete" ,name: "app_system_delete" )]
    public function delete(SystemRepository $systemRepository, $id, ManagerRegistry $managerRegistry): Response {
        // Get the Entity Manager
        $em = $managerRegistry->getManager();
    
        // Find the system entity by its ID
        $system = $systemRepository->find($id);
    
        // Get the subscriptions associated with the system
        $subscriptions = $system->getSubscriptions();
    
        // Loop through each subscription and remove it
        foreach ($subscriptions as $subscription) {
            $em->remove($subscription);
        }
    
        // Remove the system itself
        $em->remove($system);
    
        // Persist changes to the database
        $em->flush();
    
        // Add a success flash message
        $this->addFlash("success", "The system has been deleted");
    
        // Redirect to the system list page
        return $this->redirectToRoute("app_system");
    }
}
