<?php

// src/Form/Type/OfferFormType.php
namespace App\Form\Type;

use App\Entity\Offer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',               TextType::class)
            ->add('description',         TextType::class)
            ->add('status',              IntegerType::class)
            ->add('incorporation_date',  IntegerType::class)
        ;

    }

    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults([
            // Indicamos a que clase esta asociado nuestro formulario
            'data_class' => Offer::class,
            'allow_extra_fields' => true,
        ]);
    }
}