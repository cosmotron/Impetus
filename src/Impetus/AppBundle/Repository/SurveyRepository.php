<?php

namespace Impetus\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Impetus\AppBundle\Entity\User;
use Impetus\AppBundle\Entity\Year;


class SurveyRepository extends EntityRepository {
    public function findByYear(Year $year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT s
                                   FROM ImpetusAppBundle:Survey s
                                   WHERE s.year = :year
                                   ORDER BY s.createdAt DESC
                                   ")->setParameter('year', $year);

        return $query->getResult();
    }

    public function findByIdAndYear($id, Year $year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT s
                                   FROM ImpetusAppBundle:Survey s
                                   WHERE s.id = :id
                                       AND s.year = :year
                                   ")->setParameters(array('id' => $id,
                                                           'year' => $year));
        try {
            $result = $query->getSingleResult();
        }
        catch (\Doctrine\ORM\NoResultException $e) {
            $result = null;
        }

        return $result;
    }

    public function getSubmissionBySurveyAndUser($survey, User $user) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT ss
                                   FROM ImpetusAppBundle:SurveySubmission ss
                                   WHERE ss.survey = :survey
                                       AND ss.user = :user
                                   ")->setParameters(array('survey' => $survey,
                                                           'user' => $user));
        try {
            $result = $query->getSingleResult();
        }
        catch (\Doctrine\ORM\NoResultException $e) {
            $result = null;
        }

        return $result;
    }
}