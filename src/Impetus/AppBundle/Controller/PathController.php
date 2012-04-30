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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//use Symfony\Component\Serializer\Encoder\JsonEncoder;
//use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
//use Symfony\Component\Serializer\Serializer;


/**
 * @Route("/path")
 */
class PathController extends Controller {
    /**
     * @Route("/", name="_path")
     */
    public function getAction() {
        return $this->render('ImpetusAppBundle:Pages:path.html.twig', array('page' => 'path'));
    }

    /**
     * @Route("/nodes", name="_path_nodes", options={"expose"=true}, defaults={"_format"="json"})
     */
    public function nodesAction() {
        $em = $this->getDoctrine()->getEntityManager();

        $dql = 'SELECT node, partial prereqs.{id} FROM ImpetusAppBundle:PathNode node '.
               'LEFT JOIN node.prereqs prereqs';
        $query = $em->createQuery($dql);
        $result = $query->getArrayResult();
        $json = json_encode($result);

        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/{id}", name="_path_show", options={"expose"=true}, requirements={"id"="\d+"})
     */
    public function showAction($id) {
        $node = $this->getDoctrine()->getRepository('ImpetusAppBundle:PathNode')->find($id);

        if (!$node) {
            throw $this->createNotFoundException('Node not found with id '.$id);
        }

        return $this->render('ImpetusAppBundle:Pages:path-node.html.twig',
                             array('page' => 'path',
                                   'node' => $node));
    }
}