<?php

namespace App\Controller;

use App\Entity\Template;
use App\Repository\TemplateRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TemplateController extends AbstractController
{
    #[Route("/template", name: "app_template")]
    public function index(TemplateRepository $templateRepository): Response
    {
        return $this->render("template/index.html.twig", [
            "templates" => $templateRepository->findAll(),
        ]);
    }

    #[Route("/template/new", name: "app_template_new")]
    public function new(Request $request, ManagerRegistry $managerRegistry)
    {
        $template = new Template();
        $form = $this->createFormBuilder($template)
            ->add('title', TextType::class)
            ->add('subject', TextType::class)
            ->add("template", TextareaType::class, [
                "label" => "Content",
            ])
            ->add("save", SubmitType::class, [
                "label" => "Save",
                "attr" => [
                    "class" => "btn btn-primary",
                ],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $managerRegistry->getManager();
            $entityManager->persist($template);
            $entityManager->flush();

            return $this->redirectToRoute("app_template");
        }

        return $this->render("template/new.html.twig", [
            "form" => $form->createView(),
        ]);
    }


    
    #[Route("/template/{id}/get-template-content", name:"get_template_content")]
    public function getTemplateContent(Template $template): JsonResponse
    {
        $templateContent = $template->getTemplate();
        return new JsonResponse($templateContent);
    }
}
