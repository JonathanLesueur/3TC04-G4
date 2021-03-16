<?php

namespace App\Form;

use App\Entity\OfferComment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class OfferCommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title', TextType::class, [
            'required' => false,
            'constraints' => [
                new Length(['min' => 10, 'max' => 255, 'minMessage' => 'Veuillez indiquer un titre d\'au moins 10 caractères.'])
            ]
        ])
        ->add('content', TextareaType::class, [
            'required' => true,
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez spécifier un contenu.'
                ]),
                new Length(['min' => 20, 'max' => 700, 'minMessage' => 'Veuillez indiquer un contenu d\'au moins 100 caractères.'])
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OfferComment::class,
        ]);
    }
}
