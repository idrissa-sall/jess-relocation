<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, array(
                'label'     => 'Nom et Prénom',
                'required'  => true,
                'attr'      => [
                    'placeholder'   => 'Nom et Prénom',
                    'class'         => 'form-control'
                ]
            ))
            ->add('email', EmailType::class, array(
                'label'     => 'Email',
                'required'  => true,
                'attr'      => [
                    'placeholder'   => 'Email',
                    'class'         => 'form-control'
                ]
            ))
            ->add('subject', TextType::class, array(
                'label'     => 'Sujet',
                'required'  => true,
                'attr'      => [
                    'placeholder'   => 'Sujet',
                    'class'         => 'form-control'
                ],
                'constraints' => [
                    new Length([
                        'min'           => 5,
                        'minMessage'    => "Minimum 5 caractères",
                        'max'           => 50,
                        'maxMessage'    => 'Maximum 50 caractères'
                    ])
                ]
            ))
            ->add('message', TextareaType::class, array(
                'label'     => 'Message',
                'required'  => true,
                'attr'      => [
                    'class'         => 'form-control',
                    'placeholder'   => 'Message'
                ],
                'constraints' => [
                    new Length([
                        'min'           => 20,
                        'minMessage'    => "Minimum 20 caractères",
                    ])
                ]
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
