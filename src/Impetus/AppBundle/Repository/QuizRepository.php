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
                                   ORDER BY q.createdAt DESC
                                   ")->setParameter('year', $year);

        return $query->getResult();
    }

    public function getQuizListByUserAndYear(User $user, Year $year) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT q.id,
                                       q.name,
                                       q.createdAt,
                                       a1.submittedAt AS mostRecentAttempt,
                                       COUNT(qu) as totalQuestions,
                                       (
                                           SELECT COUNT(r)
                                           FROM ImpetusAppBundle:QuizAttempt a3
                                           INNER JOIN a3.quizResults r
                                           WHERE r.quizAttempt = a1
                                               AND r.correct = TRUE
                                       ) AS correctAnswers
                                   FROM ImpetusAppBundle:Quiz q
                                   LEFT JOIN q.quizAttempts a1
                                   LEFT JOIN q.questions qu
                                   WHERE (
                                             a1.submittedAt = (
                                                 SELECT MAX(a2.submittedAt)
                                                 FROM ImpetusAppBundle:QuizAttempt a2
                                                 WHERE a2.quiz = q
                                             )
                                             OR a1.submittedAt IS NULL
                                         )
                                       AND q.year = :year
                                       AND ( a1.user = :user OR a1.user IS NULL)
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
}