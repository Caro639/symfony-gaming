<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control mt-4',
                    'placeholder' => 'Votre nom',
                ],
                'label' => 'Nom',
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 255])
                ]
            ])
            ->add('nickname', TextType::class, [
                'attr' => [
                    'class' => 'form-control mt-4',
                    'placeholder' => 'Votre pseudo',
                ],
                'label' => 'Votre pseudo',
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 255])
                ]
            ])
            // ->add('profileImage', TextType::class, [
            //     'attr' => [
            //         'class' => 'form-control mt-4',
            //         'placeholder' => 'Votre nom',
            //     ],
            //     'label' => 'Nom',
            //     'constraints' => [
            //         new Assert\Length(['min' => 2, 'max' => 255])
            //     ]
            // ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                'label' => 'Pays',
                'placeholder' => 'Sélectionnez un pays',
                //                'multiple' => true, =collection array
//                'expanded' => true, = checkbox
                'attr' => [
                    'class' => 'form-select mt-4'
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control mt-4',
                    'minlength' => 5,
                    'maxlength' => 180,
                    'placeholder' => 'Votre adresse e-mail',
                ],
                'label' => 'Votre E-mail',
                'constraints' => [
                    new Assert\Email(),
                    new Assert\Length(['min' => 5, 'max' => 180])
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control mt-4',
                    'placeholder' => 'Votre mot de passe',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
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
            'data_class' => User::class,
        ]);
    }
}
