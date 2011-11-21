<?php

namespace Impetus\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Impetus\AppBundle\Entity\District;
use Impetus\AppBundle\Entity\Role;
use Impetus\AppBundle\Entity\Year;


class RosterRepository extends EntityRepository {
    public function findByYear(Year $year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT r FROM ImpetusAppBundle:Roster r
                                   WHERE r.year = :year'
                                  )->setParameter('year', $year);

        return $query->getResult();
    }

    public function findOneByDistrictAndYear(District $district, Year $year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT r FROM ImpetusAppBundle:Roster r
                                   WHERE r.district = :district AND r.year = :year'
                                  )->setParameters(array('district' => $district,
                                                         'year' => $year)
                                                   );

        return $query->getSingleResult();
    }

    public function findByTAAndYear($user, Year $year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT r
                                   FROM ImpetusAppBundle:Roster r
                                   INNER JOIN r.assistants t
                                   WHERE r.year = :year
                                       AND t = :user
                                   ")->setParameters(array('user' => $user,
                                                           'year' => $year));

        return $query->getResult();
    }

    public function findByTeacherAndYear($user, Year $year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT r
                                   FROM ImpetusAppBundle:Roster r
                                   INNER JOIN r.teachers t
                                   WHERE r.year = :year
                                       AND t = :user
                                   ")->setParameters(array('user' => $user,
                                                           'year' => $year));

        return $query->getResult();
    }
}