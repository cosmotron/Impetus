<?php

/*
 * This file is part of the ImpetusAppBundle
 *
 * (c) Ryan Lewis <lewis.ryan.j@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Impetus\AppBundle\Controller;

use Impetus\AppBundle\Entity\Quiz;
use Impetus\AppBundle\Entity\QuizAttempt;
use Impetus\AppBundle\Entity\QuizResult;
use Impetus\AppBundle\Form\QuizType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;


/**
 * @Route("/quiz")
 */
class QuizController extends BaseController {
    /**
     * @Route("/delete", name="_quiz_delete", options={"expose"=true}, requirements={"id"="\d+", "_method"="POST"})
     * @Secure(roles="ROLE_TA")
     */
    public function deleteAction(Request $request) {
        $em = $this->getDoctrine()->getEntityManager();

        $quizId = $request->request->get('id');

        $quiz = $em->getRepository('ImpetusAppBundle:Quiz')->find($quizId);

        $em->remove($quiz);
        $em->flush();

        return new Response('success');
    }

    /**
     * @Route("/{id}/edit", name="_quiz_edit", requirements={"id"="\d+"})
     * @Secure(roles="ROLE_TA")
     */
    public function editAction($id, Request $request) {

    }

    /**
     * @Route("/help", name="_quiz_help")
     * @Secure(roles="ROLE_TA")
     */
    public function helpAction() {
        return $this->render('ImpetusAppBundle:Quiz:quiz-help.html.twig', array('page' => 'quiz'));
    }

    /**
     * @Route("/", name="_quiz_list")
     * @Secure(roles="ROLE_STUDENT")
     */
    public function listAction() {
        $doctrine = $this->getDoctrine();
        $year = $this->get('year_service')->getCurrentAcademicYear();

        if ($this->hasAssistantAuthority()) {
            $quizzes = $doctrine->getRepository('ImpetusAppBundle:Quiz')->findByYear($year);
            return $this->render('ImpetusAppBundle:Quiz:quiz-list-creator.html.twig',
                                 array('page' => 'quiz',
                                       'quizzes' => $quizzes));
        }
        else {
            $user = $this->getCurrentUser();
            $quizzes = $doctrine->getRepository('ImpetusAppBundle:Quiz')->getQuizListByUserAndYear($user, $year);

            return $this->render('ImpetusAppBundle:Quiz:quiz-list-user.html.twig',
                                 array('page' => 'quiz',
                                       'quizzes' => $quizzes));
        }
        /*
        echo '<pre>';
        print_r($quizzes);
        echo '</pre>';

        return new Response();
        */
    }

    /**
     * @Route("/{id}/attempts", name="_quiz_list_attempts", requirements={"id"="\d+"})
     * @Secure(roles="ROLE_STUDENT")
     */
    public function listAttemptsAction($id) {
        $doctrine = $this->getDoctrine();
        $user = $this->getCurrentUser();
        $year = $this->get('year_service')->getCurrentAcademicYear();
        $quiz = $doctrine->getRepository('ImpetusAppBundle:Quiz')->findByIdAndYear($id, $year);
        if (!$quiz) {
            throw $this->createNotFoundException('Quiz not found with id '.$id.' and year '.$year->getYear());
        }

        $quizAttempts = $doctrine->getRepository('ImpetusAppBundle:Quiz')->getQuizAttemptsByQuizAndUser($quiz, $user);

        return $this->render('ImpetusAppBundle:Quiz:quiz-attempts.html.twig',
                             array('page' => 'quiz',
                                   'quiz' => $quiz,
                                   'quizAttempts' => $quizAttempts));
    }

    /**
     * @Route("/new", name="_quiz_new")
     * @Secure(roles="ROLE_TA")
     */
    public function newAction(Request $request) {
        $quiz = new Quiz();
        $quizForm = $this->createForm(new QuizType(), $quiz);

        if ($request->getMethod() == 'POST') {
            $quizForm->bindRequest($request);

            if ($quizForm->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();

                $year = $this->get('year_service')->getCurrentAcademicYear();
                $quiz->setYear($year);

                $em->persist($quiz);
                $em->flush();

                $this->get('session')->setFlash('notice', 'Quiz created!');

                return $this->redirect($this->generateUrl('_quiz_list'));
            }
        }

        return $this->render('ImpetusAppBundle:Quiz:quiz-new.html.twig',
                             array('page' => 'quiz',
                                   'quizForm' => $quizForm->createView()));
    }

    /**
     * @Route("/results", name="_quiz_results")
     * @Secure(roles="ROLE_TA")
     */
    public function resultsAction() {
        $doctrine = $this->getDoctrine();

        $rosters = $this->getAuthorizedRosters();
        if (!$rosters) {
            throw new HttpException(403, 'You do not have access to this data.');
        }

        $year = $this->get('year_service')->getCurrentAcademicYear();
        $quizzes = $doctrine->getRepository('ImpetusAppBundle:Quiz')->findByYear($year);

        $results = array();
        foreach ($rosters as $roster) {
            $districtKey = $roster->getDistrict()->getName();

            foreach ($roster->getStudents() as $student) {
                $studentKey = $student->getUser()->getLastName().', '.$student->getUser()->getFirstName();

                foreach ($quizzes as $quiz) {
                    $latestAttempt = $doctrine->getRepository('ImpetusAppBundle:Quiz')->getLatestQuizAttemptByQuizAndUser($quiz, $student->getUser());

                    $results[$districtKey][$studentKey][$quiz->getId()] = $latestAttempt;
                }
            }
        }
        /*
        echo '<pre>';
        print_r($results);
        echo '</pre>';
        */
        return $this->render('ImpetusAppBundle:Quiz:quiz-results.html.twig',
                             array('page' => 'quiz',
                                   'quizzes' => $quizzes,
                                   'results' => $results));
    }

    /**
     * @Route("/{id}", name="_quiz_show", options={"expose"=true}, requirements={"id"="\d+"})
     * @Secure(roles="ROLE_STUDENT")
     */
    public function showAction($id, Request $request) {
        $doctrine = $this->getDoctrine();
        $user = $this->getCurrentUser();
        $year = $this->get('year_service')->getCurrentAcademicYear();
        $quiz = $doctrine->getRepository('ImpetusAppBundle:Quiz')->findByIdAndYear($id, $year);
        if (!$quiz) {
            throw $this->createNotFoundException('Quiz not found with id '.$id.' and year '.$year->getYear());
        }

        $correctAnswers = $doctrine->getRepository('ImpetusAppBundle:Quiz')->getCorrectAnswersByQuiz($quiz);

        if ($request->getMethod() == 'POST') {
            $userAnswers = $request->request->get('user_answer');

            // TODO: Move to service, possibly
            if (count($userAnswers) != count($correctAnswers)) {
                throw new HttpException(400, 'Bad request (check answers)');
            }
            for ($i = 0; $i < count($userAnswers); $i++) {
                if ($userAnswers[$i] == null) {
                    throw new HttpException(400, 'Bad request (problem '.($i+1).' is null)');
                }
            }

            $em = $doctrine->getEntityManager();

            $quizAttempt = new QuizAttempt($quiz, $user);
            $em->persist($quizAttempt);
            $em->flush();

            $count = 0;
            foreach ($correctAnswers as $correctAnswer) {
                $correct = ($userAnswers[$count] == $correctAnswer->getValue()) ? 1 : 0;
                $quizResult = new QuizResult($userAnswers[$count], $correct, $quizAttempt, $correctAnswer->getQuestion());

                $em->persist($quizResult);
                $em->flush();

                $quizAttempt->addQuizResult($quizResult);

                $count++;
            }

            $em->flush();

            return $this->redirect($this->generateUrl('_quiz_show_attempt',
                                                      array('quizId' => $id,
                                                            'attemptId' => $quizAttempt->getId())));
        }

        return $this->render('ImpetusAppBundle:Quiz:quiz-new-attempt.html.twig',
                             array('page' => 'quiz',
                                   'quiz' => $quiz));
    }

    /**
     * @Route("/{quizId}/attempt/{attemptId}", name="_quiz_show_attempt", requirements={"quizId"="\d+", "attemptId"="\d+"})
     * @Secure(roles="ROLE_STUDENT")
     */
    public function showAttemptAction($quizId, $attemptId) {
        $doctrine = $this->getDoctrine();

        $quizAttempt = $doctrine->getRepository('ImpetusAppBundle:Quiz')->findByIdAndAttemptId($quizId, $attemptId);
        if (!$quizAttempt) {
            throw $this->createNotFoundException('Quiz Attempt '.$attemptId.' not found for quiz with id ' . $quizId);
        }

        if (!$this->hasAssistantAuthority()
            && $quizAttempt->getUser()->getId() != $this->getCurrentUser()->getId()) {
            throw new HttpException(403, 'You do not have access to this data.');
        }

        return $this->render('ImpetusAppBundle:Quiz:quiz-attempt.html.twig',
                             array('page' => 'quiz',
                                   'quiz' => $quizAttempt->getQuiz(),
                                   'quizAttempt' => $quizAttempt,
                                   'user_answer' => $quizAttempt->getQuizResults()->toArray()));
    }
}