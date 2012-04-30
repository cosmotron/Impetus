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
     * @Secure(roles="ROLE_TEACHER")
     */
    public function indexAction() {
        return $this->render('ImpetusAppBundle:Analytics:analytics.html.twig',
                             array('page' => 'analytics'));
    }

    /**
     * @Route("/participant-roster.{_format}", name="_analytics_participant_roster", defaults={"_format"="html"}, options={"expose"=true}, requirements={"_format"="html|csv"})
     * @Secure(roles="ROLE_TEACHER")
     */
    public function participantRosterAction($_format) {
        $year = $this->get('year_service')->getCurrentAcademicYear();

        $results = $this->getDoctrine()->getRepository('ImpetusAppBundle:Student')->findByYear($year);

        $response = $this->get('simple_result');
        $response->init($results,
                        $_format,
                        'participant-roster.csv',
                        'participant-roster.html.twig');

        return $response->generate();
    }

    /**
     * @Route("/students-served.{_format}", name="_analytics_students_served", defaults={"_format"="html"}, options={"expose"=true}, requirements={"_format"="html|csv"})
     * @Secure(roles="ROLE_TEACHER")
     */
    public function studentsServedAction($_format) {
        $year = $this->get('year_service')->getCurrentAcademicYear();
        $analyticsService = $this->get('analytics_service');

        // Male students
        $results['maleTable'] = $analyticsService->createEthnicityTable('Male', $year);
        $results['maleRowTotals'] = $analyticsService->tableRowSums($results['maleTable'], true);
        $results['maleColumnTotals'] = $analyticsService->tableColumnSums($results['maleTable'], true);


        // Female students
        $results['femaleTable'] = $analyticsService->createEthnicityTable('Female', $year);
        $results['femaleRowTotals'] = $analyticsService->tableRowSums($results['femaleTable']);
        $results['femaleColumnTotals'] = $analyticsService->tableColumnSums($results['femaleTable'], true);

        $response = $this->get('students_served_result');
        $response->init($results,
                        $_format,
                        'students-served.csv',
                        'students-served.html.twig');

        return $response->generate();
    }

    /**
     * @Route("/graduate-placement.{_format}", name="_analytics_graduate_placement", defaults={"_format"="html"}, options={"expose"=true}, requirements={"_format"="html|csv"})
     * @Secure(roles="ROLE_TEACHER")
     */
    public function graduatePlacementAction($_format) {
        $year = $this->get('year_service')->getCurrentAcademicYear();

        $results = $this->getDoctrine()->getRepository('ImpetusAppBundle:User')->getGraduatePlacementsByYear($year);

        $response = $this->get('simple_result');
        $response->init($results,
                        $_format,
                        'graduate-placement.csv',
                        'graduate-placement.html.twig');

        return $response->generate();
    }

    /**
     * @Route("/activities-summary.{_format}", name="_analytics_activities_summary", defaults={"_format"="html"}, options={"expose"=true}, requirements={"_format"="html|csv"})
     * @Secure(roles="ROLE_TEACHER")
     */
    public function activitiesSummaryAction($_format) {
        $year = $this->get('year_service')->getCurrentAcademicYear();
        $analyticsService = $this->get('analytics_service');

        $activities = $this->getDoctrine()->getRepository('ImpetusAppBundle:Activity')->findAll();

        $results = $analyticsService->createActivitiesTable($activities, $year);

        $response = $this->get('complex_result');
        $response->init($results,
                        $_format,
                        'activity',
                        'activities-summary.csv',
                        'activities-summary.html.twig');

        return $response->generate();
    }
}