<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use App\Form\Type\ObsterType;
use App\Form\Type\OmsType;
use App\Form\Type\TempType;
use App\Form\Type\Tension1Type;
use App\Form\Type\Tension2Type;
use App\Form\Type\PoulsType;
use App\Form\Type\respType;
use App\Form\Type\OxygeneType;

class MedicalType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hta')
            ->add('diabete')
            ->add('asthme')
            ->add('drepano')
            ->add('tuberculose')
            ->add('arthrose')
            ->add('chabdo')
            ->add('chpulmo')
            ->add('chortho')
            ->add('cesarienne')
            ->add('autres')
            ->add('obsteg', ObsterType::class)
            ->add('obstep', ObsterType::class)
            ->add('psychiatrique')
            ->add('oms', OmsType::class,['placeholder' => 'Oms'])
            ->add('temper', TempType::class,['placeholder' => 'Temperature'])
            ->add('tensionone', Tension1Type::class,['placeholder' => 'Tension'])
            ->add('tensiontwo', Tension2Type::class,['placeholder' => 'Arterielle'])
            ->add('pouls', PoulsType::class,['placeholder' => 'Pouls'])
            ->add('respir', respType::class,['placeholder' => 'Frequence Respiratoire'])
            ->add('oxygene', OxygeneType::class,['placeholder' => 'Saturation en O2'])
            ->add('coeuretvais')
            ->add('pulmo')
            ->add('neuro')
            ->add('autreexam')
            ->add('diagnostic')
            ->add('enceinte')
            ->add('charriot')
            ->add('trait1')
            ->add('trait2')
            ->add('trait3')
            ->add('trait4')
            ->add('trait5')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Medical'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mecquebundle_medical';
    }
}
