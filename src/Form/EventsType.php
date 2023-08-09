<?php

namespace App\Form;

use App\Entity\Events;
use App\Entity\System;
use App\Entity\Template;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('system', EntityType::class, [
            "class" => System::class,
            "choice_label" => "name",
            
        ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Maintenance' => 'maintenance',
                    'Incident' => 'incident',
                ],
            ])
            ->add('start', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('end', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('emailtemplate', EntityType::class, [
                'class' => Template::class,
                'choice_label' => 'template',
                'label' => 'Select Template', // Adjust label as needed
            ])
            ->add('email', TextareaType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Email Template',
                'attr' => ['rows' => 5], // Adjust rows as needed
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Events::class,
        ]);
    }
}
