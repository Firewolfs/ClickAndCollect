<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => true
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => true
            ])
            ->add('email', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => true
            ])
            ->add('adresse', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => true
            ])
            ->add('codePostal', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => true
            ])
            ->add('ville', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => true
            ])
            ->add('telephone', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => true
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'type' => PasswordType::class,
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'first_options'   => [
                    'attr'               => [ 'class' => 'form-control' ]
                ],
                'second_options'  => [
                    'attr'               => [ 'class' => 'form-control' ]
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
