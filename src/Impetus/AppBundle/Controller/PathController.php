<?php

namespace Impetus\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class PathController extends Controller {
    public function getAction() {
        return $this->render('ImpetusAppBundle:Pages:path.html.twig', array('page' => 'path'));
    }
}