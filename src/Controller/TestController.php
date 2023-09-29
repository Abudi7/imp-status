<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        echo"<pre>";
        for ($i=5; $i <=109; $i++) { 
            echo"INSERT INTO `subscription` (user_id,system_id,is_subscribed) VALUES ($i,1,1);";
            echo"<br>";
        }
        exit();
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
