<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Form\Type\PensionType;

class PackType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('designation')
            ->add('montant')
            ->add('limite', DateType::class, array( 'widget' => 'single_text','attr' => ['title' => 'Date limite de paiement'],))
            ->add('taux')
            ->add('exploitation')
            ->add('aerien1')
            ->add('eriencout1')
            ->add('aerien2')
            ->add('eriencout2')
            ->add('aerien3')
            ->add('eriencout3')
            ->add('hebergementmecque')
            ->add('hebergementmecquecout')
            ->add('hebergementmedine')
            ->add('hebergementmedinecout')
            ->add('pensionmecque', PensionType::class, array('placeholder' => 'Sélectionnez pension'))
            ->add('pensionmecquecout')
            ->add('pensionmedine', PensionType::class, array('placeholder' => 'Sélectionnez pension'))
            ->add('pensionmedinecout')
            ->add('terrestreordinaire')
            ->add('terrestreordinairecout')
            ->add('terrestrevip')
            ->add('terrestrevipcout')
            ->add('terrestreautre')
            ->add('terrestreautrecout')
            ->add('vipservice1')
            ->add('vipservice1cout')
            ->add('vipservice2')
            ->add('vipservice2cout')
            ->add('vipservice3')
            ->add('vipservice3cout')
            ->add('taxe')
            ->add('taxecout')
            ->add('mouna')
            ->add('mounacout')
            ->add('restomouna')
            ->add('restomounacout')
            ->add('assurance')
            ->add('assurancecout')
            ->add('vaccin')
            ->add('vaccincout')
            ->add('pecule')
            ->add('peculecout')
            ->add('zamzam')
            ->add('zamzamcout')
            ->add('guide')
            ->add('guidecout')
            ->add('medecin')
            ->add('medecincout')
            ->add('administratif')
            ->add('administratifcout')
            ->add('autreservice')
            ->add('autreservicecout')
            ->add('promotion1')
            ->add('promotion1cout')
            ->add('promotion2')
            ->add('promotion2cout')
            ->add('promotion3')
            ->add('promotion3cout')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Pack'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mecquebundle_pack';
    }
}
