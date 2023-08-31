<?php

namespace App\Controller;

use App\Entity\Template;
use App\Repository\TemplateRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Maintenance' => 'maintenance',
                    'Incident' => 'incident',
                ]])
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

    #[Route("/template/{id}", name: "app_template_show")]
    public function showTemplate(Template $template): Response
    {
        return $this->render("template/show.html.twig", [
            "template" => $template,
        ]);
    }

    /**
     * Edit an existing template.
     *
     * @param Request $request The HTTP request
     * @param Template $template The template entity to edit
     * @param ManagerRegistry $managerRegistry The manager registry for entity management
     * @return Response
     */
    #[Route("/template/{id}/edit", name: "app_template_edit")]
    public function edit(Request $request, Template $template, ManagerRegistry $managerRegistry): Response
    {
        // Create a form for editing the template
        $form = $this->createFormBuilder($template)
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Maintenance' => 'maintenance',
                    'Incident' => 'incident',
                ]])
            ->add('title', TextType::class)
            ->add('subject', TextType::class)
            ->add("template", TextareaType::class, [
                "label" => "Content",
                "attr" => ["rows" => 5],  // Add this line to set the number of rows
            ])           
            ->add("save", SubmitType::class, [
                "label" => "Save",
                "attr" => [
                    "class" => "btn btn-primary",
                ],
            ])
            ->getForm();

        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Get the entity manager and update the template in the database
            $entityManager = $managerRegistry->getManager();
            $entityManager->flush();

            // Redirect back to the template index page
            return $this->redirectToRoute("app_template");
        }

        // Render the edit template form
        return $this->render("template/edit.html.twig", [
            "form" => $form->createView(),
        ]);
    }
    
    
    /**
     * Get the content of a template.
     *
     * @param Template $template The template entity to retrieve content from
     * @return JsonResponse The JSON response containing the template content
     */
    #[Route("/template/{id}/get-template-content", name:"get_template_content")]
    public function getTemplateContent(Template $template): JsonResponse
    {
        // Get the template content from the entity
        $templateContent = $template->getTemplate();
               
        // Return the template content as JSON response
        return new JsonResponse($templateContent);
    }
    
    /**
     * Get the subject of a template.
     *
     * @param Template $template The template entity to retrieve subject from
     * @return JsonResponse The JSON response containing the template subject
     */
    #[Route("/template/{id}/get-template-subject", name:"get_template_subject")]
    public function getTemplateSubject(Template $template): JsonResponse
    {
        // Get the template subject from the entity
        $templateSubject = $template->getSubject();
                
        // Return the template subject as JSON response
        return new JsonResponse($templateSubject);
    }
}
