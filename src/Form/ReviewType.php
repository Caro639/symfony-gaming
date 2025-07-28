<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Review;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Votre commentaire',
                'attr' => [
                    'class' => 'form-control mt-4'
                ]
            ])
//            ->add('createdAt', null, [
//                'widget' => 'single_text',
//            ])
//            ->add('downVote', IntegerType::class, [
//                'label' => 'Votes négatifs',
//                'attr' => [
//                    'class' => 'form-control mt-4',
//                    'min' => 0,
//                    'max' => 100
//                ]
//            ])
//            ->add('upVote', IntegerType::class, [
//                'label' => 'Votes positifs',
//                'attr' => [
//                    'class' => 'form-control mt-4',
//                    'min' => 0,
//                    'max' => 100
//                ]
//            ])
            ->add('rating', NumberType::class, [
                'label' => 'Note de (0 à 5)',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Range(min: 0, max: 5),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'button-gradient mt-4'
                ]
            ])
//            ->add('user', EntityType::class, [
//                'class' => User::class,
//                'choice_label' => 'id',
//            ])
//            ->add('game', EntityType::class, [
//                'class' => Game::class,
//                'choice_label' => 'id',
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
