<?php

// src/Form/Type/UserFormType.php
namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',      TextType::class)
            ->add('firstname',  TextType::class)
            ->add('lastname',   TextType::class)
            ->add('birth_date', IntegerType::class)
        ;

    }

    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults([
            // Indicamos a que clase esta asociado nuestro formulario
            'data_class' => User::class,
            'allow_extra_fields' => true,
        ]);
    }
}