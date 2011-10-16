<?php

namespace Impetus\AppBundle\Controller;

use Impetus\AppBundle\Entity\User;
use Impetus\AppBundle\Form\Type\CreateUserType;
use Impetus\AppBundle\Form\Type\UpdateUserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class UserController extends BaseController {
    public function createAction() {
        $user = new User();
        $form = $this->createForm(new CreateUserType(), $user);

        return $this->render('ImpetusAppBundle:Pages:user.html.twig',
                             array('page' => 'user',
                                   'type' => 'Create User',
                                   'form' => $form->createView(),
                                   'message' => ''));
    }

    public function createProcessAction(Request $request) {
        $user = new User();
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
        }

        return $this->render('ImpetusAppBundle:Pages:user.html.twig',
                             array('page' => 'user',
                                   'type' => 'Create User',
                                   'form' => $form->createView(),
                                   'message' => 'error'));
    }

    public function listAction() {
        $repository = $this->getDoctrine()->getRepository('ImpetusAppBundle:USER');
        $users = $repository->findAll();

        return $this->render('ImpetusAppBundle:Pages:user-list.html.twig',
                             array('page' => 'user',
                                   'users' => $users));
    }

    public function readAction(User $user) {
        return $this->render('ImpetusAppBundle:Pages:profile.html.twig', array('page' => 'profile',
                                                                               'user' => $user));
    }

    public function updateAction($id) {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('ImpetusAppBundle:User')->find($id);
        /*
        $districtId = $user->getDistrict()->getId();

        if (false === $this->checkDistrictAuthority($districtId) &&
            false === $this->get('security.context')->isGranted('MASTER', $user)) {
            throw new AccessDeniedException();
        }
        */
        $form = $this->createForm(new UpdateUserType(), $user);

        return $this->render('ImpetusAppBundle:Pages:user.html.twig',
                             array('page' => 'user',
                                   'type' => 'Update User',
                                   'form' => $form->createView(),
                                   'message' => ''));
    }

    public function updateProcessAction($id, Request $request) {

    }
}
