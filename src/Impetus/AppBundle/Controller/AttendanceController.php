<?php

/*
 * This file is part of the ImpetusAppBundle
 *
 * (c) Ryan Lewis <lewis.ryan.j@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Impetus\AppBundle\Controller;

use Impetus\AppBundle\Entity\Event;
use Impetus\AppBundle\Entity\EventAttendee;
use Impetus\AppBundle\Entity\User;
use Impetus\AppBundle\Form\EventType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * @Route("/attendance")
 */
class AttendanceController extends BaseController {
    /**
     * @Route("/event/new", name="_attendance_event_new")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function eventNewAction(Request $request) {
        $event = new Event();
        $form = $this->createForm(new EventType(), $event);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $year = $this->get('year_service')->getCurrentAcademicYear();
                $event->setYear($year);

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($event);
                $em->flush();

                $this->get('session')->setFlash('notice', 'Event created!');

                return $this->redirect($this->generateUrl('_attendance_list'));
            }
        }

        return $this->render('ImpetusAppBundle:Attendance:attendance-event-new.html.twig',
                             array('page' => 'attendance',
                                   'form' => $form->createView())
                             );
    }

    /**
     * @Route("/", name="_attendance_list")
     * @Secure(roles="ROLE_TEACHER")
     */
    public function listAction() {
        $doctrine = $this->getDoctrine();
        $events = $doctrine->getRepository('ImpetusAppBundle:Event')->findAll();

        return $this->render('ImpetusAppBundle:Attendance:attendance-list.html.twig',
                             array('page' => 'attendance',
                                   'events' => $events));
    }

    /**
     * @Route("/{id}", name="_attendance_show")
     * @Secure(roles="ROLE_TEACHER")
     */
    public function showAction(Request $request, $id) {
        $doctrine = $this->getDoctrine();

        $event = $doctrine->getRepository('ImpetusAppBundle:Event')->find($id);

        if (!$event) {
            throw $this->createNotFoundException('No event found for id ' . $id);
        }

        if ($this->hasAdminAuthority()) {
            $users = $doctrine->getRepository('ImpetusAppBundle:User')->findAllOrderedByLastName();
        }
        else {
            $rosters = $this->getAuthorizedRosters();
            if (!$rosters) {
                throw new HttpException(403, 'You do not have access to this data.');
            }

            $users = array();
            foreach ($rosters as $roster) {
                foreach($roster->getStudents() as $student) {
                    $users[] = $student->getUser();
                }
            }
        }

        return $this->render('ImpetusAppBundle:Attendance:attendance-event-show.html.twig',
                             array('page' => 'attendance',
                                   'event' => $event,
                                   'users' => $users)
                             );
    }
}