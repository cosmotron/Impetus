<?php

namespace Impetus\AppBundle\Service;

use Symfony\Component\HttpFoundation\Response;


class CsvService {
    public function create($results) {
        $csv = '';

        $csv .= $this->headerRow($results[0]);
        $csv .= $this->dataRows($results);

        return $csv;
    }

    public function createHttpResponse($results, $filename) {
        //echo '<pre>';
        $csv = $this->create($results);
        //echo $csv;
        //echo '</pre>';
        $response = new Response($csv);

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment;filename='.$filename);

        return $response;
    }

    private function headerRow($result) {
        $columnHeaders = array_keys($result);

        return implode(',', $columnHeaders) . "\n";
    }

    private function dataRows($results) {
        $data = '';

        foreach($results as $row) {
            $line = '';
            $delim = '';

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