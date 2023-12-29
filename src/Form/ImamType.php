<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\Type\SexeType;
use App\Form\Type\BloodGroupType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ImamType extends AbstractType
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
			->add('datenaiss', DateType::class, array( 'widget' => 'single_text', 'html5' => false,'attr' => ['class' => 'js-datepicker'],))
			->add('lieunaiss')
			->add('image', ImageType::class)
			->add('adresse')
			->add('sexe', SexeType::class,array('placeholder' => 'Sélectionnez le genre'))
			->add('numpassport')
			->add('numsaoudiantax')
			->add('phone')
			->add('famillyphone')
			->add('bloodgroup', BloodGroupType::class, array('placeholder' => 'Sélectionnez le groupe sanguin'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Imam'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mecquebundle_imam';
    }
}
