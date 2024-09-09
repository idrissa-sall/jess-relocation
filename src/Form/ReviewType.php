<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label'         => 'Nom et Prénom',
                'required'      => true,
                'attr'          => [
                    'class'         => 'form-control',
                    'placeholder'   => 'Nom et Prénom',
                ],
                'constraints'   => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide.',
                    ]),
                ]
            ])
            ->add('message', TextareaType::class, [
                'label'         => 'Message',
                'required'      => true,
                'attr'          => [
                    'class'         => 'form-control',
                    'placeholder'   => 'Message',
                ],
                'constraints'   => [
                    new Length([
                        'min'           => 3,
                        'minMessage'    => "Minimum 3 caractères",
                        'max'           => 125,
                        'maxMessage'    => 'Maximum 125 caractères'
                    ]),
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide.',
                    ]),
                ]
            ])
            ->add('profil_picture', FileType::class, [
                'mapped'        => false,
                'label'         => 'Vous pouvez joindre une photo de profil (jpg, jpeg, png)',
                'required'      => false,
                'attr'          => [
                    'class'         => 'form-control'
                ],
                'constraints'   => [
                    new File([
                        'maxSize'   => '8M',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Formats acceptés *.jpg, .jpeg, .png',
                        'maxSizeMessage' => 'La taille maximale autorisée est de 8Mo'
                    ])
                ]
            ])
            // ->add('submition_date', null, [
            //     'widget' => 'single_text',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
