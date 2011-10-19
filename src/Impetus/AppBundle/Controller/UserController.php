<?php

namespace Impetus\AppBundle\Controller;

use Impetus\AppBundle\Entity\User;
use Impetus\AppBundle\Form\Type\CreateUserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/user")
 */
class UserController extends BaseController {
    /**
     * @Route("/{id}/edit", name="_user_edit", requirements={"id"="\d+"})
     */
    public function editAction($id, Request $request) {

    }

    /**
     * @Route("/", name="_user_list")
     */
    public function listAction() {

    }

    /**
     * @Route("/new", name="_user_new")
     */
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

    /**
     * @Route("{type}/search", name="_user_search", options={"expose"=true})
     */
    public function searchAction($type, Request $request) {
        $repository = $this->getDoctrine()->getRepository('ImpetusAppBundle:User');

        // TODO: add permission checks. e.g. non-admins shouldn't be able to query for admin usernames

        switch ($type) {
            case 'assistant':
                $users = $repository->findByApproximateAssistantName($request->query->get('term'));
                break;
            case 'teacher':
                $users = $repository->findByApproximateTeacherName($request->query->get('term'));
                break;
            case 'student':
                $users = $repository->findByApproximateUnenrolledStudentName($request->query->get('term'));
                break;
            default:
                throw $this->createNotFoundException('"'. $type . '" is and invalid user type');
        }

        $json = json_encode($users);

        return new Response($json);
    }

    /**
     * @Route("/{id}", name="_user_show", requirements={"id"="\d+"})
     */
    public function showAction($id) {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getEntityManager();

        $user = $doctrine->getRepository('ImpetusAppBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException('No user found for id ' . $id);
        }

        $district = $em->createQuery('SELECT d.name, s.grade, y.year
                                      FROM ImpetusAppBundle:Roster r
                                      INNER JOIN r.students s
                                          INNER JOIN s.user u
                                      INNER JOIN r.district d
                                      INNER JOIN r.year y
                                      WHERE y.year = 2011 AND u.id = :id'
                                     )->setParameter('id', $id)->getSingleResult();

        //$grades = $doctrine->getRepository('ImpetusAppBundle:Grade')->findAllByUser($user);

        return $this->render('ImpetusAppBundle:Pages:user-show.html.twig',
                             array('page' => 'user',
                                   'user' => $user,
                                   'district' => $district,
                                   )
                             );
    }
}