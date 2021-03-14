<?php

namespace App\Form;

use App\Entity\RapidPost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RapidPostResponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer un titre à votre message.'
                    ]),
                    new Length(['min' => 10, 'max' => 255, 'minMessage' => 'Veuillez indiquer un titre d\'au moins 10 caractères.'])
                ]
            ])
            ->add('content', TextareaType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez spécifier un contenu.'
                    ]),
                    new Length(['min' => 100, 'max' => 700,'minMessage' => 'Veuillez indiquer un contenu d\'au moins 100 caractères.'])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RapidPost::class,
        ]);
    }
}
