<?php

namespace App\Controller;

use App\WebSocketServer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebsocketController extends AbstractController
{
    #[Route('/websocket', name: 'app_websocket')]
    public function index(): Response
    {

        // Return a response or leave it empty as per your requirements
        return $this->render('websocket/index.html.twig');
    }
}
