<?php

namespace App\Form;

use App\Entity\Status;
use App\Entity\System;
use App\Entity\SystemStatus;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class SystemStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('system', EntityType::class, [
                'class' => System::class,
                'choice_label' => 'name',
                'placeholder' => 'Choose a system',
                'required' => true,
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'name',
                'placeholder' => 'Choose a status',
                'constraints' => [
                    new NotNull(),
                ],
                'data' => $options['Available'], // set the default value here
                //'disabled' => !$builder->getDisabled(), // disable field for new records only
            ])
            
            // ->add('createdAt', DateTimeType::class, [
            //     'widget' => 'single_text',
            //     'html5' => false,
            //     'attr' => [
            //         'class' => 'js-datepicker',
            //         'autocomplete' => 'off',
            //     ],
            // ])
            // ->add('updatedAt',DateTimeType::class, [
            //     'widget' => 'single_text',
            //     'html5' => true,
            //     'attr' => [
            //         'class' => 'js-datepicker',
            //         'autocomplete' => 'off',
            //     ],
            // ])
            //Responsible Person
            //->add('author')
            ->add('send',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SystemStatus::class,
            'Available' => 0, // default value for the status field
        ]);
    }
}
