<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use App\Form\Type\VerserType;

class VersementAgenceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('montant')
			->add('type', VerserType::class,array('placeholder' => 'Type de versement'))
			->add('banque')
			->add('numero')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\VersementAgence'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mecquebundle_versementagence';
    }
}
