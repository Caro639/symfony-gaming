<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Publisher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublisherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('createdAt', null, [
//                'widget' => 'single_text',
//            ])
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'éditeur',
                'attr' => [
                    'class' => 'form-control mt-4',
                    'placeholder' => 'Entrez le nom du Publisher',
                ],
            ])
//            ->add('slug')
            ->add('website', UrlType::class, [
                'label' => 'Site Web',
                'attr' => [
                    'class' => 'form-control mt-4',
                    'placeholder' => 'Entrez le site web du Publisher',
                ]
            ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                'label' => 'Pays',
                'placeholder' => 'Sélectionnez un pays',
//                'multiple' => true,
//                'expanded' => true,
                'attr' => [
                    'class' => 'form-select mt-4'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'button-gradient mt-4'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Publisher::class,
        ]);
    }
}
