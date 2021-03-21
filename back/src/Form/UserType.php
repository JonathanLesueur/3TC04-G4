<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
        ->add('formation', TextType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez indiquer le nom de votre formation.'
                ]),
                new Length(['min' => 5, 'max' => 255,'minMessage' => 'Le nom de votre formation doit contenir au moins 5 caractères.',])
            ]
        ])
            ->add('avatar', FileType::class, [
                'mapped' => false,
                'required' => false,
                'data_class' => null,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez choisir une image à un format valide : PNG ou JPG, et de 1Mo de poids max.',
                    ])
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
