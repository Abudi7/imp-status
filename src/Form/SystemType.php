<?php

namespace App\Form;

use App\Entity\System;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SystemType extends AbstractType
{
    private $tokenStorage;
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->tokenStorage->getToken()->getUser();

        $builder

        ->add('name')
        // ->add('createdAt', DateTimeType::class, [
        //     'widget' => 'single_text',
        //     'html5' => true,
        //     'attr' => [
        //         'class' => 'js-datepicker',
        //         'autocomplete' => 'off',
        //     ],
        // ])
        ->add('send',SubmitType::class)

            ->add('name', TextType::class)
            ->add('active', null, [
                'data' => true, // Set default value to false
            ])
            ->add('info', TextType::class)
            // ->add('responsible_person', TextType::class, [
            //     'data' => $user->getUserIdentifier(), // Set the username as the default value
            //     'disabled' => false,
                
            // ])
            ->add('send', SubmitType::class);

    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => System::class,
        ]);
    }
}
