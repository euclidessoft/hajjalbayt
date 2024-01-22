<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\repository\HotelRepository;
use App\Form\Type\PlaceType;
use App\Form\Type\RoomType;
use App\Entity\Hotel;

class ChambreMecqueType extends AbstractType
{
	
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$id = $options['id'];
        $builder
            ->add('numeroreel')
            ->add('nombre')
            ->add('type', RoomType::class, array('placeholder'=>'Type de chambre'))
            ->add('place', PlaceType::class, array('placeholder'=>'Nombre de lits'))
			->add('hotel', EntityType::class, 
		array( 'class' => Hotel::class,
		'choice_label' => 'nom',
		'multiple' => false,
		'query_builder' => function(HotelRepository $repository) use ($id) { return $repository->hotelmecque($id); },
		'placeholder' => 'Selectionnez hôtel'))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\ChambreMecque',
            'id' => null,
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'mecquebundle_chambremecque';
    }
}
