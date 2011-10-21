<?php

namespace Impetus\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Impetus\AppBundle\Entity\User;


class StudentRepository extends EntityRepository {
    public function findOneByUserIdAndYear($userId, $year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT s
                                   FROM ImpetusAppBundle:Student s
                                   INNER JOIN s.user u
                                   INNER JOIN s.roster r
                                       INNER JOIN r.year y
                                   WHERE u.id = :userId AND y = :year'
                                  )->setParameters(array('userId' => $userId,
                                                         'year' => $year));

        return $query->getSingleResult();
    }
}