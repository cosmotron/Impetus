<?php

namespace Impetus\AppBundle\Controller;

use Impetus\AppBundle\Entity\Quiz;
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
     * @Route()
     * @Secure(roles="ROLE_TA")
     */
    public function editAction($id, Request $request) {

    }

    /**
     * @Route("/", name="_quiz_list")
     * @Secure(roles="ROLE_STUDENT")
     */
    public function listAction() {
        return $this->render('ImpetusAppBundle:Pages:quiz_list.html.twig', array('page' => 'quiz'));
    }

    /**
     * @Route("/new", name="_quiz_new", options={"expose"=true})
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
     * @Route("{id}", name="_quiz_show", options={"expose"=true}, requirements={"id"="\d+"})
     * @Secure(roles="ROLE_STUDENT")
     */
    public function showAction($id, Request $request) {
        return $this->render('ImpetusAppBundle:Pages:quiz.html.twig', array('page' => 'quiz',
                                                                            'id' => $id));
    }

}