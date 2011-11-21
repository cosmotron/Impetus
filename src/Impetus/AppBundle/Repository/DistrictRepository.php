<?php

namespace Impetus\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Impetus\AppBundle\Entity\User;


class DistrictRepository extends EntityRepository {
    public function findAllOrderedByName() {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT u
                                   FROM ImpetusAppBundle:District u
                                   ORDER BY u.name ASC");

        return $query->getResult();
    }
}