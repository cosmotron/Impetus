<?php

namespace Impetus\AppBundle\Controller;

use Impetus\AppBundle\Entity\Survey;
use Impetus\AppBundle\Entity\SurveySubmission;
use Impetus\AppBundle\Entity\SurveyResult;
use Impetus\AppBundle\Form\SurveyType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;


/**
 * @Route("/survey")
 */
class SurveyController extends BaseController {
    /**
     * @Route("/{id}/edit", name="_survey_edit", requirements={"id"="\d+"})
     * @Secure(roles="ROLE_TA")
     */
    public function editAction() {
        ;
    }

    /**
     * @Route("/", name="_survey_list")
     * @Secure(roles="ROLE_PARENT")
     */
    public function listAction() {
        $doctrine = $this->getDoctrine();
        $year = $this->get('year_service')->getCurrentAcademicYear();

        $surveys = $doctrine->getRepository('ImpetusAppBundle:Survey')->findByYear($year);

        if ($this->hasAdminAuthority()) {
            return $this->render('ImpetusAppBundle:Survey:survey-list-creator.html.twig',
                                 array('page' => 'survey',
                                       'surveys' => $surveys));
        }
        else {
            return $this->render('ImpetusAppBundle:Survey:survey-list-user.html.twig',
                                 array('page' => 'survey',
                                       'surveys' => $surveys));
        }
    }

    /**
     * @Route("/new", name="_survey_new")
     * @Secure(roles="ROLE_TA")
     */
    public function newAction(Request $request) {
        $survey = new Survey();
        $surveyForm = $this->createForm(new SurveyType, $survey);

        if ($request->getMethod() == 'POST') {
            $surveyForm->bindRequest($request);

            if ($surveyForm->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();

                $year = $this->get('year_service')->getCurrentAcademicYear();
                $survey->setYear($year);

                $em->persist($survey);
                $em->flush();

                $this->get('session')->setFlash('notice', 'Survey created!');
            }
        }

        return $this->render('ImpetusAppBundle:Survey:survey-new.html.twig',
                             array('page' => 'survey',
                                   'surveyForm' => $surveyForm->createView()));
    }

    /**
     * @Route("/{id}", name="_survey_show", options={"expose"=true}, requirements={"id"="\d+"})
     * @Secure(roles="ROLE_PARENT")
     */
    public function showAction($id, Request $request) {
        $doctrine = $this->getDoctrine();
        $user = $this->getCurrentUser();
        $year = $this->get('year_service')->getCurrentAcademicYear();
        $survey = $doctrine->getRepository('ImpetusAppBundle:Survey')->findByIdAndYear($id, $year);
        if (!$survey) {
            throw $this->createNotFoundException('Survey not found with id '.$id.' and year '.$year->getYear());
        }

        $submission = $doctrine->getRepository('ImpetusAppBundle:Survey')->getSubmissionBySurveyAndUser($survey, $user);
        if ($submission) {
            throw new HttpException(403, 'Survey with id '.$id.' has already been submitted by this user.');
        }

        if ($request->getMethod() == 'POST') {
            $userAnswers = $request->request->get('user_answer');

            // TODO: Move to service, possibly
            if (count($userAnswers) != count($survey->getQuestions())) {
                throw new HttpException(400, 'Bad request (check answers)');
            }
            for ($i = 0; $i < count($userAnswers); $i++) {
                if ($userAnswers[$i] == null) {
                    throw new HttpException(400, 'Bad request (problem '.($i+1).' is null)');
                }
            }

            $em = $doctrine->getEntityManager();

            $surveySubmission = new SurveySubmission($survey, $user);
            $em->persist($surveySubmission);
            $em->flush();

            $count = 0;
            foreach ($survey->getQuestions() as $surveyQuestion) {
                $surveyResult = new SurveyResult($userAnswers[$count], $surveySubmission, $surveyQuestion);

                $em->persist($surveyResult);
                $em->flush();

                $surveySubmission->addSurveyResult($surveyResult);

                $count++;
            }

            $em->flush();

            $this->get('session')->setFlash('notice', 'Survey submitted!');
        }

        return $this->render('ImpetusAppBundle:Survey:survey-list.html.twig',
                             array('page' => 'survey',
                                   'survey' => $survey));
    }
}