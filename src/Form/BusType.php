<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\repository\CompagnieRepository;
use App\Entity\Compagnie;
use App\Form\Type\GenreType;

class BusType extends AbstractType
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
            ->add('matricule')
            ->add('nbrplace')
			->add('genre', GenreType::class,array('placeholder' => 'Genre pour occuper le bus'))
            ->add('compagnie', EntityType::class, 
                array( 'class' => Compagnie::class,
                'choice_label' => 'nom',
                'multiple' => false,
                'query_builder' => function(CompagnieRepository $repository) use ($id) { return $repository->automobile($id); },
                'placeholder' => 'Sélectionnez la compagnie'))
        ;
    }
    
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Bus',
            'id' => null,
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'mecquebundle_bus';
    }
}
