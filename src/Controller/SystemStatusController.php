<?php

namespace App\Controller;

use App\Entity\Status;
use App\Entity\SystemStatus;
use App\Entity\User;
use App\Form\SystemStatusType;
use App\Repository\SystemStatusRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SystemStatusController extends AbstractController
{
    
    /**
     * =======================================
     * View all systems statuses at the moment
     * =======================================
     */
    #[Route('/system_status', name: 'app_system_status')]
    public function index(SystemStatusRepository $systemStatusRepository)
    {
        $statuses = $systemStatusRepository->findAll();
        return $this->render('system_status/index.html.twig', [
            'statuses' => $statuses
        ]);
    }


    /**
     * =================================================================
     * Method new 
     * Adding the state of a new system to the database,
     * storing it and displaying it to the user in the main interface.
     * =================================================================
     */
    
    
    #[Route('/system_status/new', name: 'app_system_status_new')]
    public function new(Request $request, ManagerRegistry $entityManager,TokenStorageInterface $tokenStorage)
    {
        $status = new SystemStatus();
        $user   = new User();
    
        $form = $this->createForm(SystemStatusType::class,  $status, [
            'Available' => $entityManager->getRepository(Status::class)->findOneBy(['name' => 'Available']),]);
    
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
    
            return $this->redirectToRoute('app_system_status');
        }
    
        return $this->render('system_status/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
 * =======================================
 * Show one system status by ID
 * =======================================
 */

#[Route('/system_status/{id}', name: 'app_system_status_show')]
public function show(SystemStatus $status)
{
    return $this->render('system_status/show.html.twig', [
        'status' => $status
    ]);
}


    /**
     * ========================================================================
     * Modify the state of the stored system in the database,
     * store it again, and display it to the user in the main interface. 
     * ========================================================================
     */
    #[Route('/system_status/{id}/edit', name: 'app_system_status_edit')]
    public function edit(Request $request, SystemStatus $status, ManagerRegistry $entityManager)
    {
        $form = $this->createForm(SystemStatusType::class, $status, [
            'Available' => $entityManager->getRepository(Status::class)->findOneBy(['name' => 'Available']),]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $entityManager->getManager();
            $status->setUpdatedAt(new  \DateTime());
            // var_dump( $status->getUpdatedAt());
            // exit();
            $entityManager->persist($status);
            $entityManager->flush();
            // Redirect to the page that displays the remaining records
            return $this->redirectToRoute('app_system_status');
        }

        return $this->render('system_status/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/system_status/{id}/delete', name: 'app_system_status_delete')]
    public function delete($id, ManagerRegistry $entityManager,SystemStatusRepository $systemStatusRepository)
    {
        // Get the repository for the entity you wish to delete
        $em = $entityManager->getManager();
        //$repository = $entityManager->getRepository(SystemStatus::class);

        // Find the record you wish to delete by ID
        $deleteSystem= $systemStatusRepository->find($id);

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
        return $this->redirectToRoute('app_system_status');
    }



    
}
