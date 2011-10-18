<?php

namespace Impetus\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Impetus\AppBundle\Entity\User;


class UserRepository extends EntityRepository {
    public function findByApproximateUnenrolledStudentName($name) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT u.id, CONCAT(u.firstName, CONCAT(' ', u.lastName)) as value
                                   FROM ImpetusAppBundle:User u
                                   WHERE (u.firstName LIKE :fname OR u.lastName LIKE :lname)
                                       AND u.id NOT IN (
                                           SELECT su.id
                                           FROM ImpetusAppBundle:Roster r
                                           INNER JOIN r.district d
                                           INNER JOIN r.students s
                                               INNER JOIN s.user su
                                           INNER JOIN r.year y
                                           WHERE y.year = 2011
                                       )")->setParameters(array('fname' => "%".$name."%",
                                                                'lname' => "%".$name."%"));

        return $query->getResult();
    }
}