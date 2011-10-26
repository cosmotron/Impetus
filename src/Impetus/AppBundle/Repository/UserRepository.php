<?php

namespace Impetus\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Impetus\AppBundle\Entity\User;


class UserRepository extends EntityRepository {
    public function findByApproximateAssistantName($name) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT u.id, CONCAT(u.firstName, CONCAT(' ', u.lastName)) as value
                                   FROM ImpetusAppBundle:User u
                                   INNER JOIN u.userRoles role
                                   WHERE (role.name = 'ROLE_TA' OR role.name = 'ROLE_MENTOR')
                                       AND (u.firstName LIKE :fname OR u.lastName LIKE :lname)
                                   ")->setParameters(array('fname' => "%".$name."%",
                                                           'lname' => "%".$name."%"));


        return $query->getResult();
    }


    public function findByApproximateUnenrolledTeacherName($name, $year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT u.id, CONCAT(u.firstName, CONCAT(' ', u.lastName)) as value
                                   FROM ImpetusAppBundle:User u
                                   INNER JOIN u.userRoles role
                                   WHERE role.name = 'ROLE_TEACHER'
                                       AND (u.firstName LIKE :fname OR u.lastName LIKE :lname)
                                       AND u.id NOT IN (
                                           SELECT t.id
                                           FROM ImpetusAppBundle:Roster r
                                           INNER JOIN r.district d
                                           INNER JOIN r.teachers t
                                           INNER JOIN r.year y
                                           WHERE y = :year
                                       )")->setParameters(array('fname' => "%".$name."%",
                                                                'lname' => "%".$name."%",
                                                                'year' => $year));

        return $query->getResult();
    }

    public function findByApproximateUnenrolledStudentName($name, $year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT u.id, CONCAT(u.firstName, CONCAT(' ', u.lastName)) as value
                                   FROM ImpetusAppBundle:User u
                                   INNER JOIN u.userRoles role
                                   WHERE role.name = 'ROLE_STUDENT'
                                       AND (u.firstName LIKE :fname OR u.lastName LIKE :lname)
                                       AND u.id NOT IN (
                                           SELECT su.id
                                           FROM ImpetusAppBundle:Roster r
                                           INNER JOIN r.district d
                                           INNER JOIN r.students s
                                               INNER JOIN s.user su
                                           INNER JOIN r.year y
                                           WHERE y = :year
                                       )")->setParameters(array('fname' => "%".$name."%",
                                                                'lname' => "%".$name."%",
                                                                'year' => $year));

        return $query->getResult();
    }
}