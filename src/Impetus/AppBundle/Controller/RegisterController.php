<?php

namespace Impetus\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Impetus\AppBundle\Form\RegisterForm;


class RegisterController extends Controller
{
    public function getAction()
    {
        $form = $this->createRegisterForm();

        return $this->render('ImpetusAppBundle:Login:register.html.twig',
                             array('form' => $form->createView()));
    }

    public function postAction(Request $request)
    {
        $form = $this->createRegisterForm();
        $form->bindRequest($request);

        if ($form->isValid()) {
            // add user to database, then redirect to login

            return $this->redirect($this->generateUrl('_login'));
        }

        return $this->render('ImpetusAppBundle:Login:register.html.twig',
                             array('form' => $form->createView()));
    }

    private function createRegisterForm()
    {
        $registerForm = new RegisterForm();

        return $this->createFormBuilder($registerForm)
            ->add('username', 'text')
            ->add('password', 'password')
            ->add('confirmPassword', 'password')
            ->getForm();
    }
}
