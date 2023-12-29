<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EthnieType extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
						'choices' => array(
							'Wolof' => 'Wolof',
                            'Pulaar' => 'Pulaar',
                            'Sérère' => 'Sérère',
                            'Diola' => 'Diola',
                            'Malinké' => 'Malinké',
                            'Socé' => 'Socé',
                            'Bambara' => 'Bambara',
                            'Diakhanké' => 'Diakhanké',
                            'Soninkés' => 'Soninkés',
                            'Manjaque' => 'Manjaque',
                            'Mancagne' => 'Mancagne',
                            'Autres' => 'Autres',
							)
						));
	}
	public function getParent()
	{
		return ChoiceType::class;
	}

}