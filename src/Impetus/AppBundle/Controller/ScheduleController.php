<?php

namespace Impetus\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ScheduleController extends Controller {
    public function getAction() {
        return $this->render('ImpetusAppBundle:Pages:schedule.html.twig', array('page' => 'schedule'));
    }
}