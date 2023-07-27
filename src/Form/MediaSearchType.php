<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('GET')
            ->add('mediaTitle', TextType::class,[
                'label' => "titre du média",
                'required' => false
            ])
            ->add('userEmail', TextType::class, [
                'label' => "Email du créateur",
                'required' => false
            ])
            ->add('mediaCreated', DateType::class, [
                'label' => "Date de création",
                'widget' => 'single_text',
                // 'format' => 'dd-MM-yyyy',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
