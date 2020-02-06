<?php

namespace App\Form;

use App\Entity\Track;
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

class TrackFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', TextType::class, [
                'label' => 'Track Number',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the track number',
                    ]),
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Track Title',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a track title',
                    ]),
                ],
            ])
            ->add('artist', TextType::class, [
                'label' => 'Track Artist',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a track artist',
                    ]),
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Save New Track'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Track::class,
        ]);
    }
}
