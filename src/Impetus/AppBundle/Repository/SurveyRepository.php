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

    public function getAnswerCountByQuestion($question, $answer) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT COUNT(sr)
                                   FROM ImpetusAppBundle:SurveyResult sr
                                   WHERE sr.surveyQuestion = :question
                                       AND sr.answer = :answer
                                   ")->setParameters(array('question' => $question,
                                                           'answer' => $answer));

        return $query->getSingleResult();
    }


    public function getResultsByQuestion($question) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT sr.answer
                                   FROM ImpetusAppBundle:SurveyResult sr
                                   WHERE sr.surveyQuestion = :question
                                   ")->setParameter('question', $question);

        return $query->getResult();
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

    public function getSubmissionCountBySurvey($survey) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT COUNT(ss)
                                   FROM ImpetusAppBundle:SurveySubmission ss
                                   WHERE ss.survey = :survey
                                   ")->setParameter('survey', $survey);

        return $query->getSingleResult();
    }

    public function getSurveyListByYear(Year $year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT s.id,
                                       s.name,
                                       s.createdAt,
                                       (
                                           SELECT MAX(ss2.submittedAt)
                                           FROM ImpetusAppBundle:SurveySubmission ss2
                                           WHERE ss2.survey = s
                                       ) AS submittedAt
                                   FROM ImpetusAppBundle:Survey s
                                   LEFT JOIN s.surveySubmissions ss
                                   WHERE s.year = :year
                                   GROUP BY s
                                   ORDER BY s.createdAt DESC")->setParameter('year', $year);

        return $query->getResult();
    }
}