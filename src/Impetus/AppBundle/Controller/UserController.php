<?php

namespace Impetus\AppBundle\Controller;

use Impetus\AppBundle\Entity\User;
use Impetus\AppBundle\Form\Type\CoreUserType;
use Impetus\AppBundle\Form\Type\CreateUserType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;


/**
 * @Route("/user")
 */
class UserController extends BaseController {
    /**
     * @Route("/{id}/edit", name="_user_edit", requirements={"id"="\d+"})
     * @Secure(roles="ROLE_ADMIN")
     */
    public function editAction($id, Request $request) {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getEntityManager();

        $user = $doctrine->getRepository('ImpetusAppBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException('No user found for id ' . $id);
        }

        $form = $this->createForm(new CoreUserType(), $user);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            // TODO: Solve issue with ignoring blank password validation
            if ($form->isValid()) {
                $em->flush();

                $this->get('session')->setFlash('notice', 'Your changes were saved!');
            }
        }

        return $this->render('ImpetusAppBundle:Pages:user-edit.html.twig',
                             array('page' => 'user',
                                   'id' => $id,
                                   'username' => $user->getUsername(),
                                   'form' => $form->createView())
                             );
    }

    /**
     * @Route("/", name="_user_list")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function listAction() {
        $users = $this->getDoctrine()->getRepository('ImpetusAppBundle:User')->findAll();

        return $this->render('ImpetusAppBundle:Pages:user-list.html.twig',
                             array('page' => 'user',
                                   'users' => $users)
                             );
    }

    /**
     * @Route("/new", name="_user_new")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function newAction(Request $request) {
        $user = new User();
        $form = $this->createForm(new CreateUserType(), $user);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                // TODO: Move to a UserService
                $user->setSalt(md5(rand()));

                $encoder = new MessageDigestPasswordEncoder('sha256', true, 10);
                $password = $encoder->encodePassword('impetus', $user->getSalt());
                $user->setPassword($password);

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($user);
                $em->flush();

                $form = $this->createForm(new CreateUserType(), new User());
                $this->get('session')->setFlash('notice', 'User created!');
            }
        }

        return $this->render('ImpetusAppBundle:Pages:user-new.html.twig',
                             array('page' => 'user',
                                   'form' => $form->createView())
                             );

        /*

            // Set ACL on District if User is TA, Mentor, or Teacher
            $role = $user->getUserRoles()->get(0)->getName();
            if ($role == "ROLE_TA" || $role == "ROLE_MENTOR" || $role == "ROLE_TEACHER") {
                $this->setAcl($user->getDistrict(), $user);
            }

            $this->setAcl($user, $user);
         */

    }

    /**
     * @Route("/{type}/search", name="_user_search", options={"expose"=true})
     */
    public function searchAction($type, Request $request) {
        $doctrine = $this->getDoctrine();
        $userRepo = $doctrine->getRepository('ImpetusAppBundle:User');

        // TODO: add permission checks. e.g. non-admins shouldn't be able to query for admin usernames

        $academicYear = $this->get('session')->get('academic_year');
        $year = $doctrine->getRepository('ImpetusAppBundle:Year')->findOneByYear($academicYear);

        switch ($type) {
            case 'assistant':
                $users = $userRepo->findByApproximateAssistantName($request->query->get('term'));
                break;
            case 'teacher':
                $users = $userRepo->findByApproximateUnenrolledTeacherName($request->query->get('term'), $year);
                break;
            case 'student':
                $users = $userRepo->findByApproximateUnenrolledStudentName($request->query->get('term'), $year);
                break;
            default:
                throw $this->createNotFoundException('"'. $type . '" is and invalid user type');
        }

        $json = json_encode($users);

        return new Response($json);
    }

    /**
     * @Route("/{id}", name="_user_show", options={"expose"=true}, requirements={"id"="\d+"})
     */
    public function showAction($id) {
        // TODO: Secure so that only shown user, parent, district ta/teach, and admin can see
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getEntityManager();

        $user = $doctrine->getRepository('ImpetusAppBundle:User')->find($id);

        $academicYear = $this->get('session')->get('academic_year');
        $year = $doctrine->getRepository('ImpetusAppBundle:Year')->findOneByYear($academicYear);

        if (!$user) {
            throw $this->createNotFoundException('No user found for id ' . $id);
        }

        $query = $em->createQuery('SELECT d.name, s.grade, y.year
                                   FROM ImpetusAppBundle:Roster r
                                   INNER JOIN r.students s
                                       INNER JOIN s.user u
                                   INNER JOIN r.district d
                                   INNER JOIN r.year y
                                   WHERE u = :user AND y = :year
                                   ')->setParameters(array('user' => $user,
                                                           'year' => $year));

        try {
            $district = $query->getSingleResult();
        }
        catch(\Doctrine\Orm\NoResultException $e) {
            $district = null;
        }

        $exams = $doctrine->getRepository('ImpetusAppBundle:ExamScore')->findByUserAndYear($user, $year);

        return $this->render('ImpetusAppBundle:Pages:user-show.html.twig',
                             array('page' => 'user',
                                   'user' => $user,
                                   'district' => $district,
                                   'exams' => $exams
                                   )
                             );
    }
}