<?php

namespace Impetus\AppBundle\Controller;

use Impetus\AppBundle\Entity\Activity;
use Impetus\AppBundle\Entity\Course;
use Impetus\AppBundle\Entity\Exam;
use Impetus\AppBundle\Entity\Year;
use Impetus\AppBundle\Form\ActivityType;
use Impetus\AppBundle\Form\CourseType;
use Impetus\AppBundle\Form\ExamType;
use Impetus\AppBundle\Form\YearType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * @Route("/config")
 */
class ConfigController extends Controller {
    /**
     * @Route("/", name="_config")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function getAction() {
        return $this->render('ImpetusAppBundle:Config:config.html.twig',
                             array('page' => 'config'));
    }

    /* #### ACTIVITY #### */

    /**
     * @Route("/activities", name="_config_activities")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function activitiesAction() {
        $doctrine = $this->getDoctrine();
        $activities = $doctrine->getRepository('ImpetusAppBundle:Activity')->findAll();

        return $this->render('ImpetusAppBundle:Config:config-activities.html.twig',
                             array('page' => 'config',
                                   'activities' => $activities));
    }

    /**
     * @Route("/activities/new", name="_config_activities_new")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function activitiesNewAction(Request $request) {
        $activity = new Activity();
        $form = $this->createForm(new ActivityType(), $activity);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($activity);
                $em->flush();

                $this->get('session')->setFlash('notice', 'Activity created!');

                return $this->redirect($this->generateUrl('_config_activities'));
            }
        }

        return $this->render('ImpetusAppBundle:Config:config-activities-new.html.twig',
                             array('page' => 'config',
                                   'form' => $form->createView())
                             );
    }

    /* #### COURSE #### */

    /**
     * @Route("/courses", name="_config_courses")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function coursesAction() {
        $doctrine = $this->getDoctrine();
        $courses = $doctrine->getRepository('ImpetusAppBundle:Course')->findAll();

        return $this->render('ImpetusAppBundle:Config:config-courses.html.twig',
                             array('page' => 'config',
                                   'courses' => $courses));
    }

    /**
     * @Route("/courses/new", name="_config_courses_new")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function coursesNewAction(Request $request) {
        $course = new Course();
        $form = $this->createForm(new CourseType(), $course);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($course);
                $em->flush();

                $this->get('session')->setFlash('notice', 'Course created!');

                return $this->redirect($this->generateUrl('_config_courses'));
            }
        }

        return $this->render('ImpetusAppBundle:Config:config-courses-new.html.twig',
                             array('page' => 'config',
                                   'form' => $form->createView())
                             );
    }

    /* #### EXAM #### */

    /**
     * @Route("/exams", name="_config_exams")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function examsAction() {
        $doctrine = $this->getDoctrine();
        $exams = $doctrine->getRepository('ImpetusAppBundle:Exam')->findAll();

        return $this->render('ImpetusAppBundle:Config:config-exams.html.twig',
                             array('page' => 'config',
                                   'exams' => $exams));
    }

    /**
     * @Route("/exams/new", name="_config_exams_new")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function examsNewAction(Request $request) {
        $exam = new Exam();
        $form = $this->createForm(new ExamType(), $exam);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($exam);
                $em->flush();

                $this->get('session')->setFlash('notice', 'Exam created!');

                return $this->redirect($this->generateUrl('_config_exams'));
            }
        }

        return $this->render('ImpetusAppBundle:Config:config-exams-new.html.twig',
                             array('page' => 'config',
                                   'form' => $form->createView())
                             );
    }

    /* #### YEAR #### */

    /**
     * @Route("/years", name="_config_years")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function yearsAction() {
        $doctrine = $this->getDoctrine();
        $years = $doctrine->getRepository('ImpetusAppBundle:Year')->findAll();

        return $this->render('ImpetusAppBundle:Config:config-years.html.twig',
                             array('page' => 'config',
                                   'years' => $years));
    }

    /**
     * @Route("/years/new", name="_config_years_new")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function yearsNewAction(Request $request) {
        $year = new Year();
        $form = $this->createForm(new YearType(), $year);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($year);
                $em->flush();

                $this->get('session')->setFlash('notice', 'Year created!');

                return $this->redirect($this->generateUrl('_config_years'));
            }
        }

        return $this->render('ImpetusAppBundle:Config:config-years-new.html.twig',
                             array('page' => 'config',
                                   'form' => $form->createView())
                             );
    }
}