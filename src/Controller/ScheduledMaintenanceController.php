<?php

namespace App\Controller;

use App\Repository\SystemStatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScheduledMaintenanceController extends AbstractController
{
    #[Route('/scheduled/maintenance', name: 'app_scheduled_maintenance')]
    public function scheduledMaintenance( SystemStatusRepository $systemStatusRepository): Response
    {
        $maintenanceEvents = $systemStatusRepository->findByStatus('Maintenance');

         
        return $this->render('scheduled_maintenance/index.html.twig', [
            'maintenanceEvents' => $maintenanceEvents,
        ]);
    }
}
