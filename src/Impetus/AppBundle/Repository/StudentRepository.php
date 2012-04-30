<?php

/*
 * This file is part of the ImpetusAppBundle
 *
 * (c) Ryan Lewis <lewis.ryan.j@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Impetus\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Impetus\AppBundle\Entity\User;


class StudentRepository extends EntityRepository {
    public function findOneByUserAndYear($user, $year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT s
                                   FROM ImpetusAppBundle:Student s
                                   INNER JOIN s.user u
                                   INNER JOIN s.roster r
                                       INNER JOIN r.year y
                                   WHERE u = :user AND y = :year'
                                  )->setParameters(array('user' => $user,
                                                         'year' => $year));

        try {
            $result = $query->getSingleResult(Query::HYDRATE_OBJECT);
        }
        catch (\Doctrine\ORM\NoResultException $e) {
            $result = null;
        }

        return $result;
    }

    public function findByYear($year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT CONCAT(u.lastName, CONCAT(', ', u.firstName)) as name,
                                          s.grade as class,
                                          d.name as school
                                   FROM ImpetusAppBundle:Student s
                                   INNER JOIN s.user u
                                   INNER JOIN s.roster r
                                       INNER JOIN r.year y
                                   INNER JOIN r.district d
                                   WHERE y = :year
                                   ORDER BY name ASC
                                   ")->setParameter('year', $year);

        return $query->getResult();
    }

    public function getActivityCountByActivityAndYear($activity, $year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT COUNT(s.id) as activityCount
                                   FROM ImpetusAppBundle:Student s
                                   INNER JOIN s.activities activities
                                       INNER JOIN activities.activity a
                                   INNER JOIN s.roster r
                                       INNER JOIN r.year y
                                   WHERE a = :activity AND
                                         y = :year
                                   ")->setParameters(array('activity' => $activity,
                                                           'year' => $year));

        return $query->getResult();
    }

    public function getEthnicityCountsByGenderAndEthnicityAndYear($gender, $ethnicity, $year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT s.grade,
                                          COUNT(u.id) as ethnicityCount
                                   FROM ImpetusAppBundle:Student s
                                   INNER JOIN s.user u
                                   INNER JOIN s.roster r
                                       INNER JOIN r.year y
                                   WHERE u.gender = :gender AND
                                         u.ethnicity = :ethnicity AND
                                         y = :year
                                   GROUP BY s.grade
                                   ")->setParameters(array('gender' => $gender,
                                                           'ethnicity' => $ethnicity,
                                                           'year' => $year));

        return $query->getResult();
    }
}