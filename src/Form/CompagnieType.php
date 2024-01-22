<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CompagnieType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class,[
                'choices' => [
                    'Aerienne' => 'Aerienne',
                    'Automobile' => 'Automobile',
                ],
                'placeholder' => 'Type de compagnie',
            ])
            ->add('nom')
            ->add('adresse')
            ->add('email')
            ->add('phone')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Compagnie'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mecquebundle_compagnie';
    }
}
