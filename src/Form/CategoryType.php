<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Game;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mt-4',
                    'placeholder' => 'Entrez le nom de la catégorie'
                ]
            ])
//            ->add('slug', TextType::class, [
//                'label' => 'slug',
//                'required' => true,
//                'attr' => [
//                    'class' => 'form-control mt-4',
//                    'placeholder' => 'Entrez le slug de la catégorie'
//                ]
//            ])
            ->add('image', TextType::class, [
                'label' => 'Image',
                'required' => false,
                'attr' => [
                    'class' => 'form-control mt-4'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'button-gradient mt-4'
                ]
            ])
//            ->add('games', EntityType::class, [
//                'class' => Game::class,
//                'choice_label' => 'id',
//                'multiple' => true,
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
