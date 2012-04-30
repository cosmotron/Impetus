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

use Impetus\AppBundle\Service\CsvService;
use Symfony\Component\HttpKernel\Exception\HttpException;


/**
 * AbstractResult
 */
abstract class AbstractResult {
    protected $csvService;
    protected $templatingService;

    public function setCsvService(CsvService $csvService) {
        $this->csvService = $csvService;
    }

    public function setTemplatingService($templatingService) {
        $this->templatingService = $templatingService;
    }

    abstract protected function generateCsvResponse();
    abstract protected function generateHtmlResponse();

    public function generate() {
        if (!$this->results) {
            throw new HttpException(404, 'No results found for the current year');
        }

        switch ($this->format) {
            case 'csv':
                return $this->generateCsvResponse();

            case 'html':
                return $this->generateHtmlResponse();

            default:
                throw new HttpException(400, 'Invalid format');
                break;
        }
    }
}