<?php

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
     * @Route("/{id}/edit")
     * @Secure(roles="ROLE_TA")
     */
    public function editAction($id, Request $request) {

    }

    /**
     * @Route("/", name="_quiz_list")
     * @Secure(roles="ROLE_STUDENT")
     */
    public function listAction() {
        $doctrine = $this->getDoctrine();

        $year = $this->get('year_service')->getCurrentAcademicYear();
        $quizzes = $doctrine->getRepository('ImpetusAppBundle:Quiz')->findByYear($year);

        return $this->render('ImpetusAppBundle:Pages:quiz-list.html.twig',
                             array('page' => 'quiz',
                                   'quizzes' => $quizzes));
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

        return $this->render('ImpetusAppBundle:Pages:quiz-attempts.html.twig',
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
            }
        }

        return $this->render('ImpetusAppBundle:Pages:quiz-new.html.twig',
                             array('page' => 'quiz',
                                   'quizForm' => $quizForm->createView()));
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

        $correctAnswers = $doctrine->getRepository('ImpetusAppBundle:Quiz')->findCorrectAnswersByQuiz($quiz);

        if ($request->getMethod() == 'POST') {
            // TODO: Make sure POST data is not garbage and is valid

            $em = $doctrine->getEntityManager();
            $userAnswers = $request->request->get('user_answer');

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

        return $this->render('ImpetusAppBundle:Pages:quiz-new-attempt.html.twig',
                             array('page' => 'quiz',
                                   'quiz' => $quiz));
    }

    /**
     * @Route("/{quizId}/attempt/{attemptId}", name="_quiz_show_attempt", requirements={"quizId"="\d+", "attemptId"="\d+"})
     * @Secure(roles="ROLE_STUDENT")
     */
    public function showAttemptAction($quizId, $attemptId) {
        $doctrine = $this->getDoctrine();

        $quiz = $doctrine->getRepository('ImpetusAppBundle:Quiz')->find($quizId);
        if (!$quiz) {
            throw $this->createNotFoundException('Quiz not found with it ' . $quizId);
        }

        $quizAttempt = $doctrine->getRepository('ImpetusAppBundle:QuizAttempt')->find($attemptId);
        if (!$quizAttempt) {
            throw $this->createNotFoundException('Quiz Attempt not found with it ' . $attemptId);
        }

        return $this->render('ImpetusAppBundle:Pages:quiz-result.html.twig',
                             array('page' => 'quiz',
                                   'quiz' => $quiz,
                                   'quizAttempt' => $quizAttempt,
                                   'user_answer' => $quizAttempt->getQuizResults()->toArray()));
    }
}