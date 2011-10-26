<?php

namespace Impetus\AppBundle\Controller;

use Impetus\AppBundle\Entity\District;
use Impetus\AppBundle\Entity\Roster;
use Impetus\AppBundle\Entity\Student;
use Impetus\AppBundle\Form\Type\NewDistrictType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * @Route("/district")
 */
class DistrictController extends BaseController {
    /**
     * @Route("/{id}/edit",
     *        name="_district_edit", requirements={"id"="\d+"})
     * @Secure(roles="ROLE_ADMIN")
     */
    public function editAction($id, Request $request) {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getEntityManager();

        $district = $doctrine->getRepository('ImpetusAppBundle:District')->find($id);

        if (!$district) {
            throw $this->createNotFoundException('No district found for id ' . $id);
        }

        $form = $this->createForm(new NewDistrictType(), $district);

        $academicYear = $this->get('session')->get('academic_year');
        $year = $doctrine->getRepository('ImpetusAppBundle:Year')->findOneByYear($academicYear);

        $roster = $district->getRosters()->filter(function($roster) use ($year) { return $roster->getYear() === $year; })->first();

        $teachers = ($roster) ? $roster->getTeachers() : null;
        $assistants = ($roster) ? $roster->getAssistants() : null;
        $students = ($roster) ? $roster->getStudents() : null;

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em->flush();

                $this->get('session')->setFlash('notice', 'Your changes were saved!');
            }
            else {
                throw new HttpException(400, 'Bad Request');
            }
        }

        return $this->render('ImpetusAppBundle:Pages:district-edit.html.twig',
                             array('page' => 'district',
                                   'id' => $id,
                                   'form' => $form->createView(),
                                   'teachers' => $teachers,
                                   'assistants' => $assistants,
                                   'students' => $students,
                                   )
                             );
    }

    /**
     * @Route("/", name="_district_list")
     */
    Public function listAction() {
        $repository = $this->getDoctrine()->getRepository('ImpetusAppBundle:District');
        $districts = $repository->findAll();

        return $this->render('ImpetusAppBundle:Pages:district-list.html.twig',
                             array('page' => 'district',
                                   'districts' => $districts));
    }

    /**
     * @Route("/new", name="_district_new")
     */
    public function newAction(Request $request) {
        $district = new District();
        $form = $this->createForm(new NewDistrictType(), $district);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($district);
                $em->flush();

                $this->get('session')->setFlash('notice', 'District created!');

                return $this->redirect($this->generateUrl('_district_list'));
            }
        }

        return $this->render('ImpetusAppBundle:Pages:district-new.html.twig',
                             array('page' => 'district',
                                   'form' => $form->createView()));
    }

    /**
     * @Route("/user/{userId}/grade", name="_district_update_grade", options={"expose"=true}, requirements={"userId"="\d+"})
     * @Method({"POST"})
     * @Secure(roles="ROLE_ADMIN")
     */
    public function updateGradeAction($userId, Request $request) {
        $newGrade = $request->request->get('grade');

        if (!preg_match('/\d+/', $newGrade)) {
            throw new HttpException('Bad Request', 400);
        }

        $doctrine = $this->getDoctrine();
        $em = $doctrine->getEntityManager();

        $repository = $doctrine->getRepository('ImpetusAppBundle:Student');

        $academicYear = $this->get('session')->get('academic_year');
        $year = $doctrine->getRepository('ImpetusAppBundle:Year')->findOneByYear($academicYear);

        $grade = $repository->findOneByUserIdAndYear($userId, $year);

        if (!$grade) {
            throw $this->createNotFoundException('No grade found for year '.$academicYear.' and user id '.$userId);
        }

        $grade->setGrade($newGrade);
        $em->flush();

        return new Response("success");
    }

    /**
     * @Route("/{districtId}/roster/{type}/{userId}/add", name="_district_roster_add", options={"expose"=true}, requirements={"districtId"="\d+", "userId"="\d+"})
     */
    public function rosterAddAction($districtId, $type, $userId) {
        return $this->rosterPerformAction($districtId, $type, $userId, 'add');
    }

    /**
     * @Route("/{districtId}/roster/{type}/{userId}/delete", name="_district_roster_remove", options={"expose"=true}, requirements={"districtId"="\d+", "userId"="\d+"})
     */
    public function rosterRemoveAction($districtId, $type, $userId) {
        return $this->rosterPerformAction($districtId, $type, $userId, 'remove');
    }

    private function rosterPerformAction($districtId, $type, $userId, $action) {
        // TODO: Move to RosterService
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getEntityManager();

        $district = $doctrine->getRepository('ImpetusAppBundle:District')->find($districtId);

        $academicYear = $this->get('session')->get('academic_year');
        $year = $doctrine->getRepository('ImpetusAppBundle:Year')->findOneByYear($academicYear);

        // Ensure a roster exists for this district-year pair
        // TODO: Move to RosterService
        try {
            $roster = $doctrine->getRepository('ImpetusAppBundle:Roster')->findOneByDistrictAndYear($district, $year);
        }
        catch (\Doctrine\ORM\NoResultException $e) {
            $roster = new Roster();
            $roster->setDistrict($district);
            $roster->setYear($year);

            $em->persist($roster);
            $em->flush();
        }

        $user = $doctrine->getRepository('ImpetusAppBundle:User')->find($userId);

        if ($type == 'assistant') {
            if ($action == 'add') {
                $roster->addAssistant($user);
            }
            else if ($action == 'remove') {
                $roster->removeAssistant($user);
            }
        }
        else if ($type ==  'teacher') {
            if ($action == 'add') {
                $roster->addTeacher($user);
            }
            else if ($action == 'remove') {
                $roster->removeTeacher($user);
            }
        }
        else if ($type == 'student') {
            if ($action == 'add') {
                $student = new Student();
                $student->setGrade(1);
                $student->setRoster($roster);
                $student->setUser($user);
                $em->persist($student);

                $roster->addStudent($student);
            }
            else if ($action == 'remove') {
                $student = $doctrine->getRepository('ImpetusAppBundle:Student')->findOneByUserIdAndYear($userId, $year);

                $roster->removeStudent($student);
                $student->setRoster(null);
                $em->remove($student);
            }
        }
        else {
            throw $this->createNotFoundException('"'. $type . '" is and invalid user type');
        }

        $em->flush();

        return new Response('success');
    }
}