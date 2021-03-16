<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('searchtext', TextType::class, [
                'required' => true,
                'mapped' => true
            ])
            ->add('contenttype', ChoiceType::class, [
                'required' => true,
                'mapped' => true,
                'choices' => [
                    'Utilisateur' => 'user',
                    'Thématique' => 'channel',
                    'Article de Blog' => 'blogpost',
                    'Message Rapide' => 'rapidpost',
                    'Offre' => 'offer',
                    'Association' => 'association'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            
        ]);
    }
}
