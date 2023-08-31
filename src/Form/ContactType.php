<?php 
// src/Form/ContactType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Your Name',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Your Email',
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Your Message',
            ])
            ->add('attachment', FileType::class, [
                'label' => 'Attachment (Optional)',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Send',
            ]);
    }
}
