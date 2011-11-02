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
        return $this->render('ImpetusAppBundle:Pages:analytics.html.twig',
                             array('page' => 'analytics'));
    }

    /**
     * @Route("/graduation_rates", name="_analytics_graduation_rates", options={"expose"=true})
     * @Secure(roles="ROLE_ADMIN")
     */
    public function fetchGraduateRatesAction() {
        $em = $this->getDoctrine()->getEntityManager();

        $query = $em->createQuery("SELECT u.graduated as year, COUNT(u.graduated) as total
                                   FROM ImpetusAppBundle:User u
                                   INNER JOIN u.userRoles r
                                   WHERE u.graduated IS NOT NULL AND
                                         r.name = 'ROLE_STUDENT'
                                   GROUP BY u.graduated
                                   ORDER BY u.graduated ASC");

        $result = $query->getResult();

        $dataTable = array();
        $dataTable['cols'] = array(
                                   array('label' => 'Year', 'type' => 'string'),
                                   array('label' => 'Students', 'type' => 'number')
                                   );
        $dataTable['rows'] = $this->dataTableRows($result, 'year', 'total');

        $response = new Response(json_encode($dataTable));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    private function dataTableRows() {
        $resultSet = func_get_arg(0);
        $numargs = func_num_args();
        $rows = array();

        foreach ($resultSet as $result) {
            $row = array();

            for ($i = 1; $i < $numargs; $i++) {
                $cell = array();

                // Make first column a string so it becomes an axis
                if ($i == 1) {
                    $cell['v'] = $result[func_get_arg($i)];
                }
                else {
                    $cell['v'] = (is_numeric($result[func_get_arg($i)])) ? intval($result[func_get_arg($i)]) : $result[func_get_arg($i)];
                }

                $row['c'][] = $cell;
            }
            $rows[] = $row;
        }

        return $rows;
    }
}