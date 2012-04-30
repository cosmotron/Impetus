<?php

/*
 * This file is part of the ImpetusAppBundle
 *
 * (c) Ryan Lewis <lewis.ryan.j@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Impetus\AppBundle\Analytics;

use Symfony\Component\HttpFoundation\Response;


/**
 * StudentsServedResult
 */
class StudentsServedResult extends AbstractResult {
    protected $results;
    protected $format;
    protected $csvName;
    protected $templateName;

    public function init($results, $format, $csvName, $templateName) {
        $this->results = $results;
        $this->format = $format;
        $this->csvName = $csvName;
        $this->templateName = $templateName;
    }

    protected function generateCsvResponse() {
        $csv = '';
        $csv .= "male\n" . $this->csvService->createComplexTable($this->results['maleTable'],
                                                                 'ethnicity');
        $csv .= 'total,' . implode(',', $this->results['maleColumnTotals']) . "\n";
        $csv .= "female\n" . $this->csvService->createComplexTable($this->results['femaleTable'],
                                                                   'ethnicity');
        $csv .= 'total,' . implode(',', $this->results['femaleColumnTotals']) . "\n";
        //return $this->get('csv_service')->generateHttpResponse($csv, 'students-served.csv');

        return $this->csvService->generateHttpResponse($csv, $this->csvName);
    }

    protected function generateHtmlResponse() {
        $response = $this->templatingService->render('ImpetusAppBundle:Analytics:'.$this->templateName,
                                                     array('page' => 'analytics',
                                                           'results' => $this->results));
        return new Response($response);
    }
}