<?php

namespace Impetus\AppBundle\Service;


class YearService {
    private $doctrine;

    private $session;

    public function __construct($doctrine, $session) {
        $this->doctrine = $doctrine;
        $this->session = $session;
    }

    public function getCurrentAcademicYear() {
        $academicYear = $this->session->get('academic_year');
        return $this->doctrine->getRepository('ImpetusAppBundle:Year')->findOneByYear($academicYear);
    }
}