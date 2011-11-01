<?php

namespace Impetus\AppBundle\Controller;

use Impetus\AppBundle\Entity\Message;
use Impetus\AppBundle\Form\Type\MessageType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;


/**
 * @Route("/message")
 */
class MessageController extends BaseController {
    /**
     * @Route("/", name="_message_list")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function listAction() {
        return $this->render('ImpetusAppBundle:Pages:message-list.html.twig',
                             array('page' => 'messages',
                                   )
                             );
    }

    /**
     * @Route("/new", name="_message_new")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function newAction(Request $request) {
        $message = new Message();
        $form = $this->createForm(new MessageType(), $message);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $this->get('session')->setFlash('notice', 'Your message was sent!');
            }
        }

        return $this->render('ImpetusAppBundle:Pages:message-new.html.twig',
                             array('page' => 'messages',
                                   'form' => $form->createView())
                             );
    }

    /**
     * @Route("/{id}", name="_message_show", requirements={"id"="\d+"})
     * @Secure(roles="ROLE_ADMIN")
     */
    public function showAction($id, Request $request) {

    }
}