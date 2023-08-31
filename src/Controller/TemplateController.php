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
    /**
 * Display the list of templates.
 *
 * This method renders the index page that displays the list of available templates.
 *
 * @Route("/template", name="app_template")
 * @param TemplateRepository $templateRepository The repository to fetch templates
 * @return Response The rendered template list page
 */
public function index(TemplateRepository $templateRepository): Response
{
    return $this->render("template/index.html.twig", [
        "templates" => $templateRepository->findAll(),
    ]);
}


    /**
     * Creates a new template.
     *
     * This method handles the creation of a new template by rendering a form for the user to fill in details,
     * including the type (Maintenance or Incident), title, subject, and content of the template. Upon form submission,
     * the template data is validated, persisted to the database, and the user is redirected back to the list of templates.
     *
     * @param Request $request The HTTP request object
     * @param ManagerRegistry $managerRegistry The manager registry to access the entity manager
     * @return Response
     *
     * @Route("/template/new", name="app_template_new")
     */
    public function new(Request $request, ManagerRegistry $managerRegistry): Response
    {
        // Create a new instance of the Template entity
        $template = new Template();
        
        // Create a form to collect template data
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

        // Handle form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the entity manager and persist the template data
            $entityManager = $managerRegistry->getManager();
            $entityManager->persist($template);
            $entityManager->flush();

            // Redirect back to the list of templates
            return $this->redirectToRoute("app_template");
        }

        // Render the form template
        return $this->render("template/new.html.twig", [
            "form" => $form->createView(),
        ]);
    }


    /**
     * Show details of a specific template.
     *
     * This method retrieves a specific template from the database and renders
     * the "show" template to display its details to the user.
     *
     * @Route("/template/{id}", name="app_template_show")
     * @param Template $template The template entity
     * @return Response
     */
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
