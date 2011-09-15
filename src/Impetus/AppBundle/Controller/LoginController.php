<?php

namespace Impetus\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Impetus\AppBundle\Form\LoginForm;


class LoginController extends Controller
{
    public function getAction()
    {
        $form = $this->createLoginForm();

        return $this->render('ImpetusAppBundle:Login:login.html.twig',
                             array('form' => $form->createView()));
    }

    public function postAction(Request $request)
    {
        $form = $this->createLoginForm();
        $form->bindRequest($request);

        if ($form->isValid()) {
            return $this->redirect($this->generateUrl('_attendance'));
        }
    }

    private function createLoginForm() {
        $loginForm = new LoginForm();

        return $this->createFormBuilder($loginForm)
            ->add('username', 'text')
            ->add('password', 'password')
            ->getForm();
    }
}
