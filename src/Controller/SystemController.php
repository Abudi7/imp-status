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
    #[Route('/system', name: 'app_system')]
    public function index(SystemRepository $systemRepository): Response
    {
        return $this->render('system/index.html.twig', [
            'systems' => $systemRepository->findAll()
        ]);
    }
    #[Route('/system/new', name:'app_system_new')]
    public function new(Request $request, ManagerRegistry $managerRegistry)
    {
        $system = new System();

        $form = $this->createForm(SystemType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $system = $form->getData();

            $managerRegistry = $managerRegistry->getManager();
            $managerRegistry->persist($system);
            $managerRegistry->flush();
            return $this->redirectToRoute('app_system_status');
        }

        return $this->render('system/new.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}
