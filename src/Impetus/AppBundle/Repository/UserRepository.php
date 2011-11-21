<?php

namespace Impetus\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Impetus\AppBundle\Entity\User;


class UserRepository extends EntityRepository {
    public function findAllOrderedByLastName() {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT u
                                   FROM ImpetusAppBundle:User u
                                   ORDER BY u.lastName");

        return $query->getResult();
    }

    public function findByApproximateName($name) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT u.id, CONCAT(u.firstName, CONCAT(' ', u.lastName)) as value
                                   FROM ImpetusAppBundle:User u
                                   WHERE CONCAT(u.firstName, CONCAT(' ', u.lastName)) LIKE :name
                                  ")->setParameter('name', "%".$name."%");

        return $query->getResult();
    }

    public function findByApproximateAssistantName($name) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT u.id, CONCAT(u.firstName, CONCAT(' ', u.lastName)) as value
                                   FROM ImpetusAppBundle:User u
                                   INNER JOIN u.userRoles role
                                   WHERE (role.name = 'ROLE_TA' OR role.name = 'ROLE_MENTOR')
                                       AND (CONCAT(u.firstName, CONCAT(' ', u.lastName)) LIKE :name)
                                   ")->setParameter('name', "%".$name."%");


        return $query->getResult();
    }


    public function findByApproximateUnenrolledTeacherName($name, $year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT u.id, CONCAT(u.firstName, CONCAT(' ', u.lastName)) as value
                                   FROM ImpetusAppBundle:User u
                                   INNER JOIN u.userRoles role
                                   WHERE role.name = 'ROLE_TEACHER'
                                       AND CONCAT(u.firstName, CONCAT(' ', u.lastName)) LIKE :name
                                       AND u.id NOT IN (
                                           SELECT t.id
                                           FROM ImpetusAppBundle:Roster r
                                           INNER JOIN r.district d
                                           INNER JOIN r.teachers t
                                           INNER JOIN r.year y
                                           WHERE y = :year
                                       )")->setParameters(array('name' => "%".$name."%",
                                                                'year' => $year));

        return $query->getResult();
    }

    public function findByApproximateUnenrolledStudentName($name, $year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT u.id, CONCAT(u.firstName, CONCAT(' ', u.lastName)) as value
                                   FROM ImpetusAppBundle:User u
                                   INNER JOIN u.userRoles role
                                   WHERE role.name = 'ROLE_STUDENT'
                                       AND CONCAT(u.firstName, CONCAT(' ', u.lastName)) LIKE :name
                                       AND u.id NOT IN (
                                           SELECT su.id
                                           FROM ImpetusAppBundle:Roster r
                                           INNER JOIN r.district d
                                           INNER JOIN r.students s
                                               INNER JOIN s.user su
                                           INNER JOIN r.year y
                                           WHERE y = :year
                                       )")->setParameters(array('name' => "%".$name."%",
                                                                'year' => $year));

        return $query->getResult();
    }

    public function findByUserRole($role) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT u
                                   FROM ImpetusAppBundle:User u
                                   INNER JOIN u.userRoles r
                                   WHERE r = :role
                                   ")->setParameter('role', $role);

        return $query->getResult();
    }

    public function getGraduatePlacementsByYear($year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT CONCAT(u.lastName, CONCAT(', ', u.firstName)) as graduate,
                                          u.diploma as diploma,
                                          u.college as college,
                                          u.major as major
                                   FROM ImpetusAppBundle:User u
                                   INNER JOIN u.graduated y
                                   WHERE y = :year
                                   ORDER BY graduate ASC
                                   ")->setParameter('year', $year);

        return $query->getResult();
    }

    public function getUsersFromDistrict($year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT CONCAT(u.lastName, CONCAT(', ', u.firstName)) as graduate,
                                          u.diploma as diploma,
                                          u.college as college,
                                          u.major as major
                                   FROM ImpetusAppBundle:User u
                                   INNER JOIN u.graduated y
                                   WHERE y = :year
                                   ORDER BY graduate ASC
                                   ")->setParameter('year', $year);

        return $query->getResult();
    }
}