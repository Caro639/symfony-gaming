<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Game;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles')
            ->add('password')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('name')
            ->add('nickname')
            ->add('profileImage')
            ->add('wallet')
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'id',
            ])
            ->add('wantedGames', EntityType::class, [
                'class' => Game::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
