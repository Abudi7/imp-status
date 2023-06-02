<?php

namespace App\Controller;

use App\Repository\SystemStatusRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScheduledMaintenanceController extends AbstractController
{
   
    #[Route('/scheduled/maintenance', name: 'app_scheduled_maintenance')]
    public function scheduledMaintenance(Request $request, SystemStatusRepository $systemStatusRepository): Response
    {
        $date = $request->query->get('date', 'month');
        $today = new DateTime('today');

        if ($date === 'week') {
            $start_date = $today->modify('this week');
            $num_weeks = 1;
        } elseif ($date === 'day') {
            $start_date = $today;
            $num_weeks = 1;
        } else {
            $start_date = new DateTime('first day of ' . $today->format('F Y'));
            $num_weeks = $start_date->format('W') !== $today->format('W') ? 6 : 5;
        }

        $maintenanceEvents = $systemStatusRepository->findByStatus('Maintenance');

        return $this->render('scheduled_maintenance/index.html.twig', [
            'maintenanceEvents' => $maintenanceEvents,
            'start_date' => $start_date,
            'num_weeks' => $num_weeks,
        ]);
    }
}
