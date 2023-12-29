<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AgenceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('adresse')
            ->add('site')
            ->add('email')
            ->add('email1')
            ->add('phone')
            ->add('phone1')
            ->add('phone2')
            ->add('caisse')
            ->add('retenu')
            ->add('responsable')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Agence'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mecquebundle_agence';
    }
}
