<?php

namespace App\Form;

use App\Entity\Album;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AlbumFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Album Title',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter an album title',
                    ]),
                ],
            ])
            ->add('artist', TextType::class, [
                'label' => 'Album Artist',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter an album artist',
                    ]),
                ],
            ])
            ->add('year', TextType::class, [
                'label' => 'Release Year',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the release year',
                    ]),
                ],
            ])
            ->add('cover', FileType::class, [
                'label' => 'Album Cover',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please add the album cover',
                    ]),
                    // new File([
                    //     'maxSize' => '3072k',
                    //     'mimeTypes' => [
                    //         'application/jpg',
                    //         'application/png',
                    //     ],
                    //     'mimeTypesMessage' => 'Please upload a valid PDF document',
                    // ])
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Save New Album'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
        ]);
    }
}
