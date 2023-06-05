<?php

namespace App\Controller;

use App\Repository\SystemStatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalenderController extends AbstractController
{
    #[Route('/calender', name: 'app_calender')]
    public function calender(Request $request , SystemStatusRepository $systemStatusRepository): Response
    {
        // Create a DateTime object for today
        $today = new \DateTime('now', new \DateTimeZone('Europe/Vienna'));
    
        // Get the date from the request query parameters
        $date = $request->query->get('date');
    
        // Set the DateTime object to the provided date or the first day of the current month
        $start_date = new \DateTime($date ?? 'first day of this month');
    
        // Set the DateTime object to the last day of the current month
        $end_date = clone $start_date;
        $end_date->modify('last day of this month');
    
        // Calculate the number of days in the previous month
        $prev_month_end = clone $start_date;
        $prev_month_end->modify('previous month')->modify('last day of this month');
        $days_in_prev_month = (int) $prev_month_end->format('d');
    
        // Calculate the number of days in the current month
        $days_in_month = (int) $end_date->format('d');
    
        // Calculate the offset to display the previous month's dates
        $offset = $start_date->format('N') - 1;
    
        // Generate an array of dates for each day of the month, including the previous month's dates
        $dates = [];
        $current_date = clone $start_date;
        $current_date->modify('-' . $offset . ' days');
        for ($i = 0; $i < 42; $i++) { // Display 6 weeks (6 rows) on the calendar
            $dates[] = clone $current_date;
            $current_date->modify('+1 day');
        }

        // Retrieve maintenance events from the system status repository with the status 'Maintenance'
        $maintenanceEvents = $systemStatusRepository->findByStatus('Maintenance');
    
        // Pass the start_date, dates, and other necessary variables to the template and render it
        return $this->render('calender/index.html.twig', [
            'maintenanceEvents' => $maintenanceEvents,
            'start_date' => $start_date,
            'dates' => $dates,
            'days_in_prev_month' => $days_in_prev_month,
            'days_in_month' => $days_in_month,
            'offset' => $offset,
        ]);
    }
    
    
}
