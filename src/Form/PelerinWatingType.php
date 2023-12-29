<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use App\Form\Type\SexeType;

class PelerinWatingType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom')
            ->add('nom')
            ->add('adresse')
            ->add('sexe', SexeType::class,array('placeholder' => 'Sélectionnez le genre *'))
            ->add('phone')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\PelerinWating'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mecquebundle_pelerinwating';
    }
}
