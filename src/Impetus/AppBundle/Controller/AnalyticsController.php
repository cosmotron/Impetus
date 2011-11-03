<?php

namespace Impetus\AppBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Impetus\AppBundle\Entity\Activity;
use Impetus\AppBundle\Entity\Student;
use Impetus\AppBundle\Entity\User;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;


/**
 * @Route("/analytics")
 */
class AnalyticsController extends BaseController {
    /**
     * @Route("/", name="_analytics")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function indexAction() {
        return $this->render('ImpetusAppBundle:Analytics:analytics.html.twig',
                             array('page' => 'analytics'));
    }

    /**
     * @Route("/participant-roster.{_format}", name="_analytics_participant_roster", defaults={"_format"="html"}, options={"expose"=true}, requirements={"_format"="html|csv"})
     * @Secure(roles="ROLE_ADMIN")
     */
    public function participantRosterAction($_format) {
        $year = $this->get('year_service')->getCurrentAcademicYear();

        $results = $this->getDoctrine()->getRepository('ImpetusAppBundle:Student')->findByYear($year);

        return $this->outputResults($results,
                                    $_format,
                                    'participant-roster.csv',
                                    'participant-roster.html.twig');
    }

    /**
     * @Route("/graduate-placement.{_format}", name="_analytics_graduate_placement", defaults={"_format"="html"}, options={"expose"=true}, requirements={"_format"="html|csv"})
     * @Secure(roles="ROLE_ADMIN")
     */
    public function graduatePlacementAction($_format) {
        $year = $this->get('year_service')->getCurrentAcademicYear();

        $results = $this->getDoctrine()->getRepository('ImpetusAppBundle:User')->getGraduatePlacementsByYear($year);

        return $this->outputResults($results,
                                    $_format,
                                    'graduate-placement.csv',
                                    'graduate-placement.html.twig');
    }


    private function outputResults($results, $format, $csvName, $templateName) {
        if (!$results) {
            throw $this->createNotFoundException('No results found for year '.$this->get('session')->get('academic_year'));
        }

        switch($format) {
            case 'csv':
                return $this->get('csv_service')->createHttpResponse($results, $csvName);
            case 'html':
                return $this->render('ImpetusAppBundle:Analytics:'.$templateName,
                                     array('page' => 'analytics',
                                           'results' => $results));
            default:
                throw $this->createNotFoundException('Invalid format');
                break;
        }
    }
}