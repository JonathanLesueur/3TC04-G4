<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer une adresse email.'
                    ]),
                    new Length(['min' => 5, 'max' => 255])
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vos devez valider ce champ pour continuer.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer un mot de passe.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins 6 caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer votre prénom.'
                    ]),
                    new Length(['min' => 3, 'max' => 255,'minMessage' => 'Votre prénom doit contenir au moins 3 caractères.',])
                ]
            ])
            ->add('lastname', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer votre nom de famille.'
                    ]),
                    new Length(['min' => 3, 'max' => 255,'minMessage' => 'Votre nom doit contenir au moins 3 caractères.',])
                ]
            ])
            ->add('university', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer le nom de votre Université.'
                    ]),
                    new Length(['min' => 5, 'max' => 255,'minMessage' => 'Le nom de votre université doit contenir au moins 5 caractères.',])
                ]
            ])
            ->add('formation', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer le nom de votre formation.'
                    ]),
                    new Length(['min' => 5, 'max' => 255,'minMessage' => 'Le nom de votre formation doit contenir au moins 5 caractères.',])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
