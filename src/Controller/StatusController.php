<?php

namespace App\Controller;

use App\Entity\Status;
use App\Form\StatusType;
use App\Repository\StatusRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatusController extends AbstractController
{
    /**
     * =====================================================
     * The first action is index(),
     * which is responsible for displaying a
     * list of all Status entities in the system.
     * It retrieves all the statuses from the database using
     * the StatusRepository object and passes them to
     * a Twig template for rendering.
     * ======================================================
     */
    #[Route("/status", name: "app_status")]
    public function index(StatusRepository $statusRepository): Response
    {
        return $this->render("status/index.html.twig", [
            "statuses" => $statusRepository->findAll(),
        ]);
    }

    /**
     * =====================================================================================
     * The second action is new(), which is responsible
     * for creating a new Status entity. It creates
     * a new Status object and uses a form created with StatusType
     * to handle the user's input. If the form is submitted and valid,
     * the new status is persisted to the database using
     * the ManagerRegistry object and the user is redirected to the app_system_status route.
     * Otherwise, the form is displayed to the user for correction.
     * ======================================================================================
     */

    #[Route("/status/new", name: "app_status_new")]
    public function new(Request $request, ManagerRegistry $managerRegistry)
    {
        $status = new Status();

        $form = $this->createForm(StatusType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $status = $form->getData();

            $managerRegistry = $managerRegistry->getManager();
            $managerRegistry->persist($status);
            $managerRegistry->flush();
            return $this->redirectToRoute("app_system_status");
        }

        return $this->render("status/new.html.twig", [
            "form" => $form->createView(),
        ]);
    }
}
