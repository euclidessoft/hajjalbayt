<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class Tension2Type extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
						'choices' => array(
							'20' => '20',
							'19' => '19',
							'18' => '18',
							'17' => '17',
							'16' => '16',
							'15' => '15',
							'14' => '14',
							'13' => '13',
							'11' => '11',
							'10' => '10',
							'9' => '9',
							'8' => '8',
							'7' => '7',
							'6' => '6',
							'5' => '5',
							'4' => '4',
							'3' => '3',
							
							)
						));
	}
	public function getParent()
	{
		return ChoiceType::class;
	}

}