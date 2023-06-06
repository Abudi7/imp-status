<?php

namespace App\Controller;

use App\Repository\SystemStatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ScheduledMaintenanceController extends AbstractController
{
   
    #[Route('/scheduled/maintenance', name: 'app_scheduled_maintenance')]
    public function scheduledMaintenance(Request $request, SystemStatusRepository $systemStatusRepository): Response
    {
        // Create a DateTime object for today
        //$today = new \DateTime('now', new \DateTimeZone('Europe/Vienna'));
         // Create a DateTime object for today in UTC timezone
        $today = new \DateTime('now', new \DateTimeZone('UTC'));

        // Get the date from the request query parameters
        $date = $request->query->get('date');

        // Set the DateTime object to the provided date or the first day of the current month
        $startDate = new \DateTime($date ?? 'first day of this month');

        // Set the DateTime object to the last day of the current month
        $endDate = clone $startDate;
        $endDate->modify('last day of this month');

        // Calculate the number of days in the previous month
        $prevMonthEnd = clone $startDate;
        $prevMonthEnd->modify('previous month')->modify('last day of this month');
        $daysInPrevMonth = (int) $prevMonthEnd->format('d');

        // Calculate the number of days in the current month
        $daysInMonth = (int) $endDate->format('d');

        // Calculate the offset to display the previous month's dates
        $offset = $startDate->format('N') - 1;

        // Generate an array of dates for each day of the month, including the previous month's dates
        $dates = [];
        $currentDate = clone $startDate;
        $currentDate->modify('-' . $offset . ' days');
        for ($i = 0; $i < 42; $i++) { // Display 6 weeks (6 rows) on the calendar
            $dates[] = clone $currentDate;
            $currentDate->modify('+1 day');
        }

        // Retrieve maintenance events from the system status repository with the status 'Maintenance'
        $maintenanceEvents = $systemStatusRepository->findByStatus('Maintenance');

        // Render the 'scheduled_maintenance/index.html.twig' template, passing the maintenance events, start date, and number of weeks
        return $this->render('scheduled_maintenance/index.html.twig', [
            'maintenanceEvents' => $maintenanceEvents,
            'startDate' => $startDate,
            'dates' => $dates,
            'daysInPrevMonth' => $daysInPrevMonth,
            'daysInMonth' => $daysInMonth,
            'offset' => $offset,
            'today' => $today, // Pass the today's DateTime object to the template
        ]);

    }
}
