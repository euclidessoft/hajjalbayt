<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RoomType extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
						'choices' => array(
							'Classique' => 'Classique',
							'Confort' => 'Confort',
							'Luxe' => 'Luxe',
							)
						));
	}
	public function getParent()
	{
		return ChoiceType::class;
	}

}