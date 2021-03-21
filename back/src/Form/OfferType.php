<?php

namespace App\Form;

use App\Entity\Offer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'mapped' => true,
                'constraints' => [
                    new Length([
                        'min' => 10,
                        'max' => 255,
                        'minMessage' => 'Veuillez indiquer un titre d\'au moins 10 caractères.'
                        ]),
                    new NotBlank([
                        'message' => 'Veuillez indiquer un titre'
                    ])
                ]
            ])
            ->add('content', TextareaType::class, [
                'required' => true,
                'mapped' => true,
                'constraints' => [
                    new Length([
                        'min' => 50,
                        'minMessage' => 'Veuillez indiquer un contenu d\'au moins 50 caractères.'
                        ]),
                    new NotBlank([
                        'message' => 'Veuillez indiquer un contenu'
                    ])
                ]
            ])
            ->add('type', ChoiceType::class, [
                'mapped' => true,
                'required' => true,
                'choices' => [
                    'Vente' => 'sale',
                    'Achat' => 'purchase',
                    'Service' => 'service',
                    'Recherche' => 'search'
                ]
            ])
            ->add('picture', FileType::class, [
                'mapped' => false,
                'required' => true,
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
            ->add('price', NumberType::class, [
                'mapped' => true,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
