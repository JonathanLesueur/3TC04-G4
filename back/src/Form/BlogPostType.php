<?php

namespace App\Form;

use App\Entity\BlogPost;
use Doctrine\DBAL\Schema\Constraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;

class BlogPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
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
                'constraints' => [
                    new Length([
                        'min' => 100,
                        'minMessage' => 'Veuillez indiquer un message d\'au moins 100 caractères.'
                        ]),
                    new NotBlank([
                        'message' => 'Veuillez indiquer un message'
                    ])
                ]
            ])
            ->add('picture', FileType::class, [
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
                        'mimeTypesMessage' => 'Veuillez choisir une image à un format valide : PNG ou JPG, et de 1Mo de poids.',
                    ])
                ]
            ])
            ->add('source', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 0,
                        'max' => 255,
                        'minMessage' => 'Veuillez indiquer une source d\'au moins 10 caractères.'
                        ])
                ]
            ]);             
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BlogPost::class,
        ]);
    }
}
