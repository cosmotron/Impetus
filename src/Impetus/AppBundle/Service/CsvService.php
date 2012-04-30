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

use Symfony\Component\HttpFoundation\Response;


class CsvService {
    public function createSimpleTable($results) {
        $csv = '';

        $csv .= $this->headerRow(reset($results));
        $csv .= $this->dataRows($results);

        return $csv;
    }

    public function createComplexTable($data, $firstColumnName) {
        $csv = '';

        $csv .= $firstColumnName . ',' . $this->headerRow(reset($data));
        $csv .= $this->dataRows($data, true);

        return $csv;
    }

    public function generateHttpResponse($csv, $filename) {
        $response = new Response($csv);

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment;filename='.$filename);

        return $response;
    }

    private function headerRow($result) {
        $columnHeaders = array_keys($result);

        return implode(',', $columnHeaders) . "\n";
    }

    private function dataRows($results, $columnKeyAsFirstElement = false) {
        $data = '';

        foreach($results as $key => $row) {
            if ($columnKeyAsFirstElement) {
                $line = $key;
                $delim = ',';
            }
            else {
                $line = '';
                $delim = '';
            }

            foreach ($row as $key => $value) {
                if (strpos($value, ',') === false) {
                    $line .= $delim . $value;
                }
                else {
                    $line .= $delim . '"' . $value . '"';
                }
                $delim = ',';
            }

            $data .= $line . "\n";
        }

        return $data;
    }
}