<?php

namespace Impetus\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;


class PathController extends Controller {
    public function getAction() {
        return $this->render('ImpetusAppBundle:Pages:path.html.twig', array('page' => 'path'));
    }

    public function nodesAction() {
        $em = $this->getDoctrine()->getEntityManager();

        $dql = 'SELECT node, partial prereqs.{id} FROM ImpetusAppBundle:PathNode node '.
               'LEFT JOIN node.prereqs prereqs';
        $query = $em->createQuery($dql);
        $result = $query->getArrayResult();
        $json = json_encode($result);

        /*
        $repository = $this->getDoctrine()->getRepository('ImpetusAppBundle:PathNode');
        $pathNodes = $repository->findAll();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()),
                                     array('json' => new JsonEncoder()));
        $json = $serializer->serialize($pathNodes, 'json');
        */

        return new Response($json);
    }

    public function showAction($id) {
        return $this->render('ImpetusAppBundle:Pages:path-node.html.twig', array('page' => 'path'));
    }
}