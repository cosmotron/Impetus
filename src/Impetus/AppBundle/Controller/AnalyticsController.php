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
     * @Route("/students-served.{_format}", name="_analytics_students_served", defaults={"_format"="html"}, options={"expose"=true}, requirements={"_format"="html|csv"})
     * @Secure(roles="ROLE_ADMIN")
     */
    public function studentsServedAction($_format) {
        $year = $this->get('year_service')->getCurrentAcademicYear();

        $maleTable = $femaleTable = array();

        $ethnicities = array('American Indian or Alaskan Native',
                             'Asian',
                             'Bi-racial',
                             'Black or African American',
                             'Hispanic',
                             'Pacific Islander',
                             'White',
                             'Other');

        // Male students
        foreach ($ethnicities as $ethnicity) {
            $results = $this->getDoctrine()->getRepository('ImpetusAppBundle:Student')->getEthnicityCountsByGenderAndEthnicityAndYear('Male', $ethnicity, $year);

            // Initialize row
            $maleTable[$ethnicity] = array_fill(7, 6, 0);

            foreach ($results as $row) {
                for ($grade = 7; $grade <= 12; $grade++) {
                    $maleTable[$ethnicity][$grade] += ($row['grade'] == $grade) ? intval($row['ethnicityCount']) : 0;
                }
            }
        }

        // Female
        foreach ($ethnicities as $ethnicity) {
            $results = $this->getDoctrine()->getRepository('ImpetusAppBundle:Student')->getEthnicityCountsByGenderAndEthnicityAndYear('Female', $ethnicity, $year);

            // Initialize row
            $femaleTable[$ethnicity] = array_fill(7, 6, 0);

            foreach ($results as $row) {
                for ($grade = 7; $grade <= 12; $grade++) {
                    $femaleTable[$ethnicity][$grade] += ($row['grade'] == $grade) ? intval($row['ethnicityCount']) : 0;
                }
            }
        }

        $maleRowTotals = $this->tableRowSums($maleTable);
        $maleColumnTotals = $this->tableColumnSums($maleTable, true);

        $femaleRowTotals = $this->tableRowSums($femaleTable);
        $femaleColumnTotals = $this->tableColumnSums($femaleTable, true);

        switch ($_format) {
            case 'csv':
                $csv = '';
                $csv .= "male\n" . $this->get('csv_service')->createComplexTable($maleTable, 'ethnicity');
                $csv .= 'total,' . implode(',', $maleColumnTotals) . "\n";
                $csv .= "female\n" . $this->get('csv_service')->createComplexTable($femaleTable, 'ethnicity');
                $csv .= 'total,' . implode(',', $femaleColumnTotals) . "\n";

                return $this->get('csv_service')->generateHttpResponse($csv, 'students-served.csv');
            case 'html':
                return $this->render('ImpetusAppBundle:Analytics:students-served.html.twig',
                                     array('page' => 'analytics',
                                           'maleTable' => $maleTable,
                                           'maleRowTotals' => $maleRowTotals,
                                           'maleColumnTotals' => $maleColumnTotals,
                                           'femaleTable' => $femaleTable,
                                           'femaleRowTotals' => $femaleRowTotals,
                                           'femaleColumnTotals' => $femaleColumnTotals));
        }
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

        switch ($format) {
            case 'csv':
                $csv = $this->get('csv_service')->createSimpleTable($results);
                return $this->get('csv_service')->generateHttpResponse($csv, $csvName);
            case 'html':
                return $this->render('ImpetusAppBundle:Analytics:'.$templateName,
                                     array('page' => 'analytics',
                                           'results' => $results));
            default:
                throw $this->createNotFoundException('Invalid format');
                break;
        }
    }

    // Expects $table[$row][$column] arrangement
    private function tableRowSums($table) {
        $totals = array();

        foreach ($table as $key => $row) {
            foreach ($row as $column) {
                $totals[$key] = (isset($totals[$key])) ? ($totals[$key] + $column) : 0;
            }
        }

        return $totals;
    }

    // Expects $table[$row][$column] arrangement
    private function tableColumnSums($table, $grand = false) {
        $totals = array();
        $grandTotal = 0;

        foreach ($table as $row) {
            foreach ($row as $key => $value) {
                $totals[$key] = (isset($totals[$key])) ? ($totals[$key] + $value) : 0;
                $grandTotal += $value;
            }
        }

        if ($grand) {
            $totals['grandTotal'] = $grandTotal;
        }

        return $totals;
    }
}