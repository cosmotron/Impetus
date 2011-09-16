<?php

namespace Impetus\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class QuizController extends Controller {
    public function listAction() {
        return $this->render('ImpetusAppBundle:Pages:quiz_list.html.twig', array('page' => 'quiz'));
    }

    public function showAction($id) {
        return $this->render('ImpetusAppBundle:Pages:quiz.html.twig', array('page' => 'quiz',
                                                                            'id' => $id));
    }

    public function newAction() {
        return $this->render('ImpetusAppBundle:Pages:quiz_new.html.twig', array('page' => 'quiz'));
    }
}