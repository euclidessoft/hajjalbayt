<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TempType extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
						'choices' => array(
							'30' => '30',
							'31' => '31',
							'32' => '32',
							'33' => '33',
							'34' => '34',
							'35' => '35',
							'36' => '36',
							'37' => '37',
							'38' => '38',
							'39' => '39',
							'40' => '40',
							'41' => '41',
							'42' => '42',
							
							)
						));
	}
	public function getParent()
	{
		return ChoiceType::class;
	}

}