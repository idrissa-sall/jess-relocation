<?php

namespace App\Form;

use App\Entity\Appointment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'     => 'Nom et Prénom',
                'required'  => true,
                'attr'      => [
                    'class'         => 'form-control',
                    'placeholder'   => 'Nom et Prénom'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide.',
                    ]),
                ]
            ])
            ->add('date_apm', DateType::class, [
                'widget'    => 'single_text',
                'required'  => true,
                'label'     => 'Jour du rendez-vous',
                'html5'     => true,
                'attr'      => [
                    'class'         => 'form-control',
                    'placeholder'   => 'Jour du rendez-vous',
                    'min' => (new \DateTimeImmutable())->format('Y-m-d'),
                ]
            ])
            ->add('hour', ChoiceType::class, [
                'label'     => 'Heure',
                'required'  => true,
                'attr'      => [
                    'class'     => 'form-select'
                ],
                'choices'   => [
                    '13h' => '13',
                    '14h' => '14',
                    '15h' => '15',
                    '16h' => '16',
                ]
            ])
            ->add('phone', TelType::class, [
                'label'     => 'Numéro de téléphone (avec indicatif si hors France)',
                'required'  => true,
                'attr'      => [
                    'class'         => 'form-control',
                    'placeholder'   => 'Numéro de téléphone (avec indicatif si hors France)',
                ],
                'constraints'   => [
                    new NotBlank([
                        'message'   => 'Ce champ ne peut pas être vide.',
                    ]),
                    new Length([
                        'min'   => 7,
                        'minMessage'    => 'Le numéro de téléphone doit comporter au moins {{limit}} caractères.'
                    ]),
                    new Regex([
                        'pattern' => '/^\+?[0-9\s\-\(\)]{10,}$/',
                        'message' => "Le numéro de téléphone n'est pas valide.",
                    ])
                ]
            ])
            ->add('reason', TextareaType::class, [
                'label'     => 'Motif du rendez-vous',
                'required'  => true,
                'attr'      => [
                    'class'         => 'form-control',
                    'placeholder'   => 'Motif du rendez-vous'
                ],
                'constraints'   => [
                    new NotBlank([
                        'message'   => 'Ce champ ne peut pas être vide.',
                    ]),
                    new Length([
                        'min'           => 15,
                        'minMessage'    => 'Minimum 15 caractères'
                    ])
                ]
            ])
            // ->add('submition_date', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('is_done')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
