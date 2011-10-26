<?php

namespace Impetus\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Impetus\AppBundle\Entity\User;
use Impetus\AppBundle\Entity\Year;


class ExamScoreRepository extends EntityRepository {
    public function findByUserAndYear(User $user, Year $year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT e
                                   FROM ImpetusAppBundle:ExamScore e
                                   INNER JOIN e.user u
                                   INNER JOIN e.year y
                                   WHERE u = :user AND y = :year
                                   ")->setParameters(array('user' => $user,
                                                           'year' => $year));

        return $query->getResult();
    }
}