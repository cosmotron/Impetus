<?php

namespace Impetus\AppBundle\Controller;

use Impetus\AppBundle\Entity\District;
use Impetus\AppBundle\Form\Type\NewDistrictType;
use Symfony\Component\HttpFoundation\Request;


class DistrictController extends BaseController {
    public function editAction($id, Request $request) {
        $doctrine = $this->getDoctrine();
        $district = $doctrine->getRepository('ImpetusAppBundle:District')->find($id);

        if (!$district) {
            throw $this->createNotFoundException('No district found for id ' . $id);
        }

        $form = $this->createForm(new NewDistrictType(), $district);

        $year = $doctrine->getRepository('ImpetusAppBundle:Year')->findOneByYear(2011);
        //$roster = $doctrine->getRepository('ImpetusAppBundle:Roster')->findOneByDistrictAndYear($district, $year);

        $roster = $district->getRosters()->filter(function($roster) use ($year) { return $roster->getYear() === $year; })->first();

        $teachers = $roster->getTeachers();
        $assistants = $roster->getAssistants();
        $students = $roster->getStudents();

        if ($request->getMethod() == 'POST') {
            // Process update
            //$this->postEditAction($form, $request);
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

    Public function listAction() {
        $repository = $this->getDoctrine()->getRepository('ImpetusAppBundle:District');
        $districts = $repository->findAll();

        return $this->render('ImpetusAppBundle:Pages:district-list.html.twig',
                             array('page' => 'district',
                                   'districts' => $districts));
    }

    public function newAction(Request $request) {
        $district = new District();
        $form = $this->createForm(new NewDistrictType(), $district);

        if ($request->getMethod() == 'POST') {
            $this->postNewAction($form, $request);
        }

        return $this->render('ImpetusAppBundle:Pages:district-new.html.twig',
                             array('page' => 'district',
                                   'form' => $form->createView()));
    }

    private function postNewAction($form, Request $request) {
        $form->bindRequest($request);

        if ($form->isValid()) {
            return $this->render('ImpetusAppBundle:Pages:district-new.html.twig',
                                 array('page' => 'district',
                                       'form' => $form->createView(),
                                       'message' => 'success'));
        }

        return $this->render('ImpetusAppBundle:Pages:district-new.html.twig',
                             array('page' => 'district',
                                   'form' => $form->createView(),
                                   'message' => 'error'));
    }
}