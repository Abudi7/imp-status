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
    public function index(SystemRepository $systemRepository, Request $request, ManagerRegistry $managerRegistry): Response
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
