<?php

namespace Impetus\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Impetus\AppBundle\Entity\Quiz;
use Impetus\AppBundle\Entity\QuizAnswer;
use Impetus\AppBundle\Entity\QuizQuestion;
use Impetus\AppBundle\Entity\QuizResult;
use Impetus\AppBundle\Entity\User;
use Impetus\AppBundle\Entity\Year;


class QuizRepository extends EntityRepository {
    public function findByIdAndYear($id, Year $year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT q
                                   FROM ImpetusAppBundle:Quiz q
                                   WHERE q.id = :id
                                       AND q.year = :year
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

    public function findByYear(Year $year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT q
                                   FROM ImpetusAppBundle:Quiz q
                                   WHERE q.year = :year
                                   ")->setParameter('year', $year);

        return $query->getResult();
    }

    public function findCorrectAnswersByQuiz(Quiz $quiz) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT qa
                                   FROM ImpetusAppBundle:QuizAnswer qa
                                   INNER JOIN qa.question qu
                                   WHERE qu.quiz = :quiz
                                       AND qa.correctAnswer = TRUE
                                   ")->setParameter('quiz', $quiz);

        return $query->getResult();
    }

   public function getNumberOfQuizAttemptsByQuizAndUser(Quiz $quiz, User $user) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT COUNT(attempt) AS attempts
                                   FROM ImpetusAppBundle:QuizAttempt attempt
                                   WHERE attempt.quiz = :quiz
                                       AND attempt.user = :user
                                   ")->setParameters(array('quiz' => $quiz,
                                                           'user' => $user));

        return $query->getSingleResult();
    }

   public function getQuizAttemptsByQuizAndUser(Quiz $quiz, User $user) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT attempt.id,
                                       attempt.submittedAt,
                                       COUNT(results) AS total,
                                       (
                                           SELECT COUNT(r2)
                                           FROM ImpetusAppBundle:QuizAttempt a2
                                           INNER JOIN a2.quizResults r2
                                           WHERE r2.quizAttempt = attempt
                                               AND r2.correct = TRUE
                                       ) AS correct
                                   FROM ImpetusAppBundle:QuizAttempt attempt
                                   INNER JOIN attempt.quizResults results
                                   WHERE attempt.quiz = :quiz
                                       AND attempt.user = :user
                                   GROUP BY attempt.submittedAt
                                   ORDER BY attempt.submittedAt DESC
                                   ")->setParameters(array('quiz' => $quiz,
                                                           'user' => $user));

        return $query->getResult();
    }
}