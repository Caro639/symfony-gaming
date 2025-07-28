<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Country;
use App\Entity\Category;
use App\Entity\Publisher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;


class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mt-4 mb-4',
                    'placeholder' => 'Entrez le nom du jeu'
                ]
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mt-4 mb-4',
                    'placeholder' => 'Entrez le prix du jeu'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'class' => 'form-control mt-4 mb-4',
                    'placeholder' => 'Entrez la description du jeu'
                ]
            ])
            // ->add('publishedAt', null, [
            //     'widget' => 'single_text',
            // ])
            ->add('thumbnailCover', TextType::class, [
                'label' => 'Image',
                'required' => false,
                'attr' => [
                    'class' => 'form-control mt-4 mb-4',
                    'placeholder' => 'Entrez l\'URL de l\'image'
                ]
            ])
            // ->add('thumbnailCover', FileType::class, [
            //     'label' => 'Veuillez choisir des fichiers images uniquement',

            //     // unmapped means that this field is not associated to any entity property
            //     'mapped' => false,

            //     // make it optional so you don't have to re-upload the PDF file
            //     // every time you edit the Product details
            //     'required' => true,

            //     // unmapped fields can't define their validation using annotations
            //     // in the associated entity, so you can use the PHP constraint classes
            //     'constraints' => [
            //         new File([
            //             'maxSize' => '5M',
            //             'mimeTypes' => [
            //                 'image/*',
            //                 'image/jpeg,',
            //                 'image/jpeg',
            //                 'image/png',
            //                 'image/gif',
            //                 'image/jpg',
            //                 'application/pdf',
            //                 'application/x-pdf',
            //             ],
            //             'mimeTypesMessage' => 'Le fichier n\'est pas valide, assurez vous d\'avoir un fichier au format PDF, PNG, JPG, JPEG',
            //         ])
            //     ],
            // ])

            // ->add('slug')
            ->add('publisher', EntityType::class, [
                'class' => Publisher::class,
                'choice_label' => 'name',
                // 'multiple' => true,
                // 'expanded' => true,
                'attr' => [
                    'class' => 'form-select mt-4 mb-4'
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'form-select mt-4 mb-4'
                ]
            ])
            ->add('countries', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'form-select mt-4 mb-4'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'button-gradient mt-4 mb-4'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
