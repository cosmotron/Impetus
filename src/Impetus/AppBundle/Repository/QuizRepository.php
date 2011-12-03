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

    public function findByIdAndAttemptId($id, $attemptId) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT qa
                                   FROM ImpetusAppBundle:QuizAttempt qa
                                   INNER JOIN qa.quiz q
                                   WHERE qa.id = :attemptId
                                       AND q.id = :id
                                   ")->setParameters(array('id' => $id,
                                                           'attemptId' => $attemptId));

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
                                   ORDER BY q.createdAt DESC
                                   ")->setParameter('year', $year);

        return $query->getResult();
    }

    public function getQuizListByUserAndYear(User $user, Year $year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT q.id,
                                       q.name,
                                       q.createdAt,
                                       (
                                           SELECT MAX(a2.submittedAt)
                                           FROM ImpetusAppBundle:QuizAttempt a2
                                           WHERE a2.quiz = q AND a2.user = :user
                                       ) AS mostRecentAttempt,
                                       (
                                           SELECT COUNT(qu2)
                                           FROM ImpetusAppBundle:Quiz q2
                                               INNER JOIN q2.questions qu2
                                           WHERE q2 = q
                                       ) AS totalQuestions,
                                       (
                                           SELECT COUNT(r.correct)
                                           FROM ImpetusAppBundle:QuizAttempt a3
                                           INNER JOIN a3.quizResults r
                                           WHERE r.correct = TRUE
                                               AND a3.user = :user
                                               AND a3.submittedAt = mostRecentAttempt
                                       ) AS correctAnswers
                                   FROM ImpetusAppBundle:Quiz q
                                   LEFT JOIN q.quizAttempts a1
                                   LEFT JOIN q.questions qu
                                   WHERE
                                       q.year = :year
                                   GROUP BY q
                                   ORDER BY q.createdAt DESC
                                   ")->setParameters(array('year' => $year, 'user' => $user));

        return $query->getResult();
    }


    public function getCorrectAnswersByQuiz(Quiz $quiz) {
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

    public function getLatestQuizAttemptByQuizAndUser(Quiz $quiz, User $user) {
        $em = $this->getEntityManager();


        $query = $em->createQuery("SELECT a1.id as attemptId,
                                       (
                                           SELECT COUNT(qu1)
                                           FROM ImpetusAppBundle:Quiz q1
                                           INNER JOIN q1.questions qu1
                                           WHERE q1 = :quiz
                                       ) AS total,
                                       (
                                           SELECT COUNT(r2)
                                           FROM ImpetusAppBundle:QuizAttempt a2
                                           INNER JOIN a2.quizResults r2
                                           WHERE r2.quizAttempt = a1
                                               AND r2.correct = TRUE
                                       ) AS correct
                                       FROM ImpetusAppBundle:QuizAttempt a1
                                       INNER JOIN a1.quizResults r1
                                       WHERE a1.quiz = :quiz
                                           AND a1.user = :user
                                           AND a1.submittedAt =
                                               (
                                                   SELECT MAX(a3.submittedAt)
                                                   FROM ImpetusAppBundle:QuizAttempt a3
                                                   WHERE a3.quiz = :quiz AND a3.user = :user
                                               )
                                       GROUP BY a1.id
                                       ")->setParameters(array('quiz' => $quiz,
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