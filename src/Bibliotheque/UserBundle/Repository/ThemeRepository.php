<?php

namespace Bibliotheque\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ThemeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ThemeRepository extends EntityRepository
{
	public function findAllOrderedByTheme()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT intitule FROM UserBundle:Theme intitule ORDER BY intitule.intitule ASC'
            )
            ->getResult();
    }
}
