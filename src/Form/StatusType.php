<?php

namespace App\Form;

use App\Entity\Status;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class StatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('createdAt', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'class' => 'js-datepicker',
                    'autocomplete' => 'off',
                ],
            ])
            ->add('send',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Status::class,
        ]);
    }
}
