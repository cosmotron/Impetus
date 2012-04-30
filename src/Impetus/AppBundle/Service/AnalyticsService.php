<?php

/*
 * This file is part of the ImpetusAppBundle
 *
 * (c) Ryan Lewis <lewis.ryan.j@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Impetus\AppBundle\Service;

use Impetus\AppBundle\Entity\Year;


class AnalyticsService {
    private $doctrine;

    public function __construct($doctrine) {
        $this->doctrine = $doctrine;
    }

    public function createActivitiesTable($activities, Year $year) {
        $table = array();

        foreach ($activities as $activity) {
            $results = $this->doctrine->getRepository('ImpetusAppBundle:Student')->getActivityCountByActivityAndYear($activity, $year);

            $table[$activity->getName()]['count'] = $results[0]['activityCount'];
        }

        return $table;
    }

    public function createEthnicityTable($gender, Year $year) {
        $table = array();
        $ethnicities = array('American Indian or Alaskan Native',
                             'Asian',
                             'Bi-racial',
                             'Black or African American',
                             'Hispanic',
                             'Pacific Islander',
                             'White',
                             'Other');

        foreach ($ethnicities as $ethnicity) {
            $results = $this->doctrine->getRepository('ImpetusAppBundle:Student')->getEthnicityCountsByGenderAndEthnicityAndYear($gender, $ethnicity, $year);

            // Initialize row
            $table[$ethnicity] = array_fill(7, 6, 0);

            foreach ($results as $row) {
                for ($grade = 7; $grade <= 12; $grade++) {
                    $table[$ethnicity][$grade] += ($row['grade'] == $grade) ? intval($row['ethnicityCount']) : 0;
                }
            }
        }

        return $table;
    }

    // Expects $table[$row][$column] arrangement
    public function tableRowSums($table) {
        $totals = array();

        foreach ($table as $key => $row) {
            foreach ($row as $column) {
                $totals[$key] = (isset($totals[$key])) ? ($totals[$key] + $column) : 0;
            }
        }

        return $totals;
    }

    // Expects $table[$row][$column] arrangement
    public function tableColumnSums($table, $grand = false) {
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