<?php

namespace Impetus\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Impetus\AppBundle\Entity\District;
use Impetus\AppBundle\Entity\Year;


class RosterRepository extends EntityRepository {
    public function findOneByDistrictAndYear(District $district, Year $year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT r FROM ImpetusAppBundle:Roster r
                                   WHERE r.district = :district AND r.year = :year'
                                  )->setParameters(array('district' => $district,
                                                         'year' => $year)
                                                   );

        return $query->getSingleResult();
    }
}