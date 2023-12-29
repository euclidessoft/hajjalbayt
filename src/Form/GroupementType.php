<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\repository\AgenceRepository;
use App\Entity\Agence;

class GroupementType extends AbstractType
{
	
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $id = $options['id'];
        $builder
            ->add('quota')
			->add('agence', EntityType::class, 
				array( 'class' => Agence::class,
				'choice_label' => 'nom',
				'multiple' => false,
				'query_builder' => function(AgenceRepository $repository) use ($id) { return $repository->Agence($id); },
				'placeholder' => 'Sélectionnez agence à ajouter'))
        ;
    }
    
     /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Groupement',
            'id' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'mecquebundle_groupement';
    }
}
