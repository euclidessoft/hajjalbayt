<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DepenseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('motif')
        ->add('montant')
        ->add('banque')
        ->add('numero')
        ->add('date')
        ->add('type', ChoiceType::class,[
                'choices' => [
                    'Espece' => 'Espece',
                    'Cheque' => 'Cheque',
                    'Virement' => 'Virement',
                    'Frais banquaire' => 'Frais banquaire'
                ],
                'placeholder' => 'Type de règlement'
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Depense'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'mecquebundle_depense';
    }


}
