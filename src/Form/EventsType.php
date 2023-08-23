<?php

namespace App\Form;

use App\Entity\Events;
use App\Entity\System;
use App\Entity\User;
use App\Entity\Template;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
            "placeholder" => "Select a system", // Optional placeholder text
            "required" => false, // Mark the field as not required
            "disabled" => true, // Set the field as disabled
        ])
        ->add('creator', EntityType::class,[
            "class" => User::class,
            "choice_label" => "email",
            "required" => false, // Mark the field as not required
            "disabled" => true, // Set the field as disabled

        ])
        ->add('created_at', DateTimeType::class, [
            'widget' => 'single_text',
            "required" => false, // Mark the field as not required
            "disabled" => true,
        ])
        ->add('type', ChoiceType::class, [
            'choices' => [
                'Maintenance' => 'maintenance',
                'Incident' => 'incident',
            ],
            
        ])
        ->add('send_email', CheckboxType::class, [
            'label' => 'Send Email',
            'required' => false,
            'attr' => [
                'class' => 'custom-switch-input', // Add a class for the switch style
            ],       
        ])       
        ->add('start', DateTimeType::class, [
            'widget' => 'single_text',
        ])
        ->add('end', DateTimeType::class, [
            'widget' => 'single_text',
        ])
        ->add('info', TextType::class, [
            'attr' => [
                'class' => 'custom-css-class',
                'placeholder' => 'Enter information...',
            ],
        ])
        ->add('emailtemplate', EntityType::class, [
            'class' => Template::class,
            'choice_label' => 'subject',
            'label' => 'Select Template', // Adjust label as needed
        ])
        ->add('email', TextareaType::class, [
            'mapped' => true,
            'required' => false,
            'label' => 'Email Template',
            'attr' => ['rows' => 5, 'class' => 'template-textarea'], // Add a class for selecting the textarea with JS
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Events::class,
        ]);
    }
}
