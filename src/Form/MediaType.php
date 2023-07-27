<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Media;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, [
                'mapped' => false
            ])
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            
            /**
             * Comment ajouter un user au formulaire
             * pas besoin parce qu'on prend l'utilisateur connecté
             */

            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'query_builder' => function (EntityRepository $er){
            //         return $er->createQueryBuilder('U')
            //             ->orderBy('U.email','ASC');               
            //     },
            //     'choice_label' => 'email',
            //     'expanded' => true
            // ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'label',
                // Besoin de cette option si c'est un tableau d'entity
                'multiple' =>true,
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
