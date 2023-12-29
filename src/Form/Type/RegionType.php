<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegionType extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
						'choices' => array(
							'Dakar' => 'Dakar',
                            'Diourbel' => 'Diourbel',
                            'Fatick' => 'Fatick',
                            'Kaffrine' => 'Kaffrine',
                            'Kaolack' => 'Kaolack',
                            'Kedougou' => 'Kedougou',
                            'Kolda' => 'Kolda',
                            'Louga' => 'Louga',
                            'Matam' => 'Matam',
                            'Saint-Louis' => 'Saint-Louis',
                            'Sedhiou' => 'Sedhiou',
                            'Tambacounda' => 'Tambacounda',
                            'Thies' => 'Thies',
                            'Ziguinchor' => 'Ziguinchor',
							)
						));
	}
	public function getParent()
	{
		return ChoiceType::class;
	}

}