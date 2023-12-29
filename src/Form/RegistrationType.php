<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('username', null,['label' => false])
        ->add('fonction', ChoiceType::class, [
            'choices' => User::jobs,
            'placeholder' => 'Fonction',
            'label' => false
        ])
            //->add('password', PasswordType::class,['label' => false])
            //->add('confirm', PasswordType::class,['label' => false])
            ->add('email', null,['label' => false])
            ->add('phone', null,['label' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
