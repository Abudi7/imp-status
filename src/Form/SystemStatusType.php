<?php

namespace App\Form;

use App\Entity\Status;
use App\Entity\System;
use App\Entity\SystemStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class SystemStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("system", EntityType::class, [
                "class" => System::class,
                "choice_label" => "name",
                "placeholder" => "Choose a system",
                "required" => true,
                "multiple" => false,
                "expanded" => false,
            ])
            ->add("status", EntityType::class, [
                "class" => Status::class,
                "choice_label" => "name",
                "placeholder" => "Choose a status",
                "constraints" => [new NotNull()],
                "data" => $options["Available"],
                "attr" => [
                    "id" => "status_field",
                ],
            ])
            ->add("info", TextType::class, [
                "label" => "Information",
                "required" => true,
                "attr" => [
                    "placeholder" => "Enter information here...",
                    "maxlength" => 100,
                ],
            ]);

            $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();
        
                // Check if the selected status is 'Maintenance'
                if ($data && $data->getStatus() == "Maintenance") {
                    $form->add('maintenanceStart', DateTimeType::class, [
                        'widget' => 'single_text',
                        'required' => false,
                        'empty_data' => null,
                        'html5' => true,
                        'attr' => [
                            'class' => 'js-datepicker',
                            'autocomplete' => 'on',
                        ],
                    ]);
                    $form->add('maintenanceEnd', DateTimeType::class, [
                        'widget' => 'single_text',
                        'required' => false,
                        'empty_data' => null,
                        'html5' => true,
                        'attr' => [
                            'class' => 'js-datepicker',
                            'autocomplete' => 'on',
                        ],
                    ]);
                }
            });

        // Add the send button
        $builder->add("send", SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => SystemStatus::class,
            "empty_data" => new SystemStatus(), // Set the default empty data as a new instance
            "Available" => null, // default value for the status field
        ]);
    }
    
}
