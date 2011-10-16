<?php

namespace Impetus\AppBundle\Controller;

use Impetus\AppBundle\Entity\User;
use Impetus\AppBundle\Form\Type\CreateUserType;
use Symfony\Component\HttpFoundation\Request;


class UserController extends BaseController {
    public function editAction($id, Request $request) {

    }

    public function listAction() {

    }

    public function newAction(Request $request) {
        $user = new User();
        $form = $this->createForm(new CreateUserType(), $user);

        $repository = $this->getDoctrine()->getRepository('ImpetusAppBundle:Year');
        $years = $repository->findAll();

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            echo $request->get('testField');

            //if ($form->isValid()) {
            //
            //}

            return $this->render('ImpetusAppBundle:Pages:user-new.html.twig',
                                 array('page' => 'user',
                                       'form' => $form->createView(),
                                       'message' => 'completed')
                                 );
        }

        return $this->render('ImpetusAppBundle:Pages:user-new.html.twig',
                             array('page' => 'user',
                                   'form' => $form->createView(),
                                   'years' => $years)
                             );

        /*

        $form = $this->createForm(new CreateUserType(), $user);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $user->setSalt(md5(rand()));

            $encoder = new MessageDigestPasswordEncoder('sha256', true, 10);
            $password = $encoder->encodePassword('impetus', $user->getSalt());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($user);
            $em->flush();

            // Set ACL on District if User is TA, Mentor, or Teacher
            $role = $user->getUserRoles()->get(0)->getName();
            if ($role == "ROLE_TA" || $role == "ROLE_MENTOR" || $role == "ROLE_TEACHER") {
                $this->setAcl($user->getDistrict(), $user);
            }

            $this->setAcl($user, $user);

            $form = $this->createForm(new CreateUserType(), new User());

            return $this->render('ImpetusAppBundle:Pages:user.html.twig',
                                 array('page' => 'user',
                                       'type' => 'Create User',
                                       'form' => $form->createView(),
                                       'message' => 'completed'));

         */

    }

    public function showAction($id) {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('ImpetusAppBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException('No user found for id ' . $id);
        }

        $grades = $em->getRepository('ImpetusAppBundle:Grade')->findAllByUser($user);

        return $this->render('ImpetusAppBundle:Pages:user-show.html.twig',
                             array('page' => 'user',
                                   'user' => $user,
                                   'grades' => $grades,
                                   )
                             );
    }
}