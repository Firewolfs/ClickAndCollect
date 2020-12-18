<?php


namespace App\Form;


use App\Entity\Produit;
use App\Entity\Stocks;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', IntegerType::class, [

            ])
        ;
        if($options['addStock'] === true){
            $builder
                ->add('produit', EntityType::class,[
                    'class'  => Produit::class,
                    'choice_label' => 'label',
                    'expanded' => false,
                    'required' => true,
                ])
            ;
        }
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stocks::class,
            'addStock' => false,
        ]);
    }
}