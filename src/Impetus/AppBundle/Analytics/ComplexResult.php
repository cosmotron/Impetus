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
 * ComplexResult
 */
class ComplexResult extends AbstractResult {
    protected $results;
    protected $format;
    protected $firstColumnName;
    protected $csvName;
    protected $templateName;

    public function init($results, $format, $firstColumnName, $csvName, $templateName) {
        $this->results = $results;
        $this->format = $format;
        $this->firstColumnName = $firstColumnName;
        $this->csvName = $csvName;
        $this->templateName = $templateName;
    }

    protected function generateCsvResponse() {
        $csv = $this->csvService->createComplexTable($this->results, $this->firstColumnName);
        return $this->csvService->generateHttpResponse($csv, $this->csvName);
    }

    protected function generateHtmlResponse() {
        $response = $this->templatingService->render('ImpetusAppBundle:Analytics:'.$this->templateName,
                                                     array('page' => 'analytics',
                                                           'results' => $this->results));
        return new Response($response);
    }
}