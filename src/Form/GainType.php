<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class GainType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('provenance')
        ->add('motif')
        ->add('montant')
         ->add('date')
         ->add('banque')
        ->add('numero')
        ->add('type', ChoiceType::class,[
                'choices' => [
                    'Espece' => 'Espece',
                    'Cheque' => 'Cheque',
                    'Virement' => 'Virement'
                ],
                'placeholder' => 'Type de financement'
            ]);        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Gain'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'mecquebundle_gain';
    }


}
