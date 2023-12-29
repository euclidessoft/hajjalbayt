<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\Type\SexeType;
use App\Form\Type\RegionType;
use App\Form\Type\EthnieType;
use App\Form\Type\LinkType;
use App\Form\Type\BloodGroupType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use App\repository\PackRepository;
use App\Entity\Pack;

class PelerinType extends AbstractType
{
	
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $id = $options['id'];
        $builder
		->add('prenom')
		->add('nom')
		->add('datenaiss', DateType::class, array( 'widget' => 'single_text','attr' => ['title' => 'Date de naissance'],))
		->add('lieunaiss')
		->add('image', ImageType::class)
		->add('adresse')
		->add('sexe', SexeType::class,array('placeholder' => 'Sélectionnez le genre *'))
		->add('numpassport')
		->add('profession')
		->add('expiredate', DateType::class, array( 'widget' => 'single_text', 'attr' => ['title' => 'Date d\'expiration'],))
		->add('numsaoudiantax')
		->add('remark')
		->add('phone')
		->add('email')
		->add('famillyphone')
		->add('famillyname')
		->add('famillylink', LinkType::class,array('placeholder' => 'Lien de parenté'))
		->add('bloodgroup', BloodGroupType::class, array('placeholder' => 'Sélectionnez le groupe sanguin'))
		->add('pack', EntityType::class, 
		array( 'class' => Pack::class,
		'choice_label' => 'designation',
		'multiple' => false,
		'query_builder' => function(PackRepository $repository) use ($id) { return $repository->current($id); },
		'placeholder' => 'Selectionnez package *'))
		->add('reduction', ReductionType::class)
		->add('diabete', CheckboxType::class)
		->add('handicap', CheckboxType::class)
		->add('nonvoyant', CheckboxType::class)
		->add('hypo', CheckboxType::class)
		->add('hyper', CheckboxType::class)
		->add('visite', CheckboxType::class)
		->add('photo', CheckboxType::class)
		->add('numdossier')
		->add('ethnie', EthnieType::class, array('placeholder' => 'Sélectionnez une ethnie'))
		->add('region', RegionType::class, array('placeholder' => 'Sélectionnez une région'))
		->add('cin');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Pelerin',
            'id' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'mecquebundle_pelerin';
    }


}
