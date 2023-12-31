<?php

namespace App\Repository;



/**
 * PackRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PackRepository extends \Doctrine\ORM\EntityRepository
{
	public function current($agence)
	{
		// On passe par le QueryBuilder vide de l'EntityManager pour l'exemple
		$query = $this ->createQueryBuilder('a') 
					->Join('a.session', 's', 'WITH', 's.current = true') 
					->addSelect('s')
					->Where('a.agence = :agence')
					->setParameter('agence', $agence)					;
					return $query;
	}
}
