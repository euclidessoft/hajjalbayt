<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\repository\CompagnieRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use App\Entity\Compagnie;

class VolType extends AbstractType
{
	
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $id = $options['id'];
        $builder
            ->add('numero')
            ->add('datedepart', DateType::class, array( 'widget' => 'single_text','attr' => ['title' => 'Date de vol'],))
            ->add('heuredepart', TimeType::class, array( 'widget' => 'single_text','attr' => ['title' => 'Heure de vol'],))
            ->add('lieudepart')
            ->add('destination')
			->add('compagnie', EntityType::class, 
		array( 'class' => Compagnie::class,
		'choice_label' => 'nom',
		'multiple' => false,
		'query_builder' => function(CompagnieRepository $repository) use ($id) { return $repository->aerienne($id); },
		'placeholder' => 'Sélectionnez la compagnie'))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Vol',
            'id' => null,
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'mecquebundle_vol';
    }
}
