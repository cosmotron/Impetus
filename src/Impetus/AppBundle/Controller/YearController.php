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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;


/**
 * @Route("/year")
 */
class YearController extends BaseController {
    /**
     * @Route("/change/{year}", name="_year_change", options={"expose"=true}, requirements={"year"="\d+"})
     */
    public function changeAction($year) {
        $doctrine = $this->getDoctrine();

        $yearEntity = $doctrine->getRepository('ImpetusAppBundle:Year')->findByYear($year);

        if (!$yearEntity) {
            throw $this->createNotFoundException('No year for id ' . $year);
        }

        $this->get('session')->set('academic_year', $year);

        return new Response('success');
    }

    /**
     * @Route("", name="_year_list", options={"expose"=true})
     */
    public function listAction() {
        $doctrine = $this->getDoctrine();

        $years = $doctrine->getRepository('ImpetusAppBundle:Year')->findAll();

        // TODO: Handle when no years exist

        $serializer = new Serializer(array(new GetSetMethodNormalizer()),
                                     array('json' => new JsonEncoder())
                                     );
        $yearsJson = $serializer->serialize($years, 'json');

        $newJson['years'] = json_decode($yearsJson);
        $newJson['current'] = $this->get('session')->get('academic_year');
        $json = json_encode($newJson);

        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}