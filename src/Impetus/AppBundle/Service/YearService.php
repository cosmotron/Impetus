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