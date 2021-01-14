<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vendeur', ChoiceType::class, [
                'label' => 'Nom',
                'attr' => [ 'class' => 'form-control' ],
                'required' => true,
                'choices' => $options['vendeurs']
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [ 'class' => 'form-control' ],
                'required' => true
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [ 'class' => 'form-control' ],
                'required' => true
            ])
            ->add('mail', TextType::class, [
                'label' => 'Mail',
                'attr' => [ 'class' => 'form-control' ],
                'required' => true
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message',
                'attr' => [ 'class' => 'form-control' ],
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'vendeurs' => []
        ]);
    }
}
