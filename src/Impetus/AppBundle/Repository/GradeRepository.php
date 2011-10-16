<?php

namespace Impetus\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Impetus\AppBundle\Entity\User;


class GradeRepository extends EntityRepository {
    public function findAllByUser(User $user) {
        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT g, y FROM ImpetusAppBundle:Grade g
                                   INNER JOIN g.year y
                                   WHERE g.user = :user')->setParameter('user', $user);

        return $query->getResult();
    }
}