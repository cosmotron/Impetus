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

use Impetus\AppBundle\Entity\Message;
use Impetus\AppBundle\Entity\MessageRecipient;
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
     * @Secure(roles="ROLE_PARENT")
     */
    public function listAction() {
        $doctrine = $this->getDoctrine();
        $user = $this->get('security.context')->getToken()->getUser();

        $messages = $doctrine->getRepository('ImpetusAppBundle:Message')->getMessageListByRecipient($user);

        //$sentMessage = $doctrine->getRepository('ImpetusAppBundle:Message')->getSentMessageListBySender($user);

        return $this->render('ImpetusAppBundle:Pages:message-list.html.twig',
                             array('page' => 'message',
                                   'messages' => $messages
                                   ));
    }

    /**
     * @Route("/new", name="_message_new", options={"expose"=true})
     * @Secure(roles="ROLE_PARENT")
     */
    public function newAction(Request $request) {
        $message = new Message();
        $form = $this->createForm(new MessageType(), $message);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            // TODO: validate recipient list
            $recipients = json_decode($request->request->get('recipients'));

            if ($form->isValid()) {
                $doctrine = $this->getDoctrine();
                $em = $doctrine->getEntityManager();
                $messageService = $this->get('message_service');

                $currentUser = $this->getCurrentUser();

                $message->setSender($currentUser);
                $em->persist($message);
                $em->flush();

                $emailAddresses = $messageService->addRecipientsToMessage($recipients, $message);

                // Sender is an implicit recipient
                $sender = new MessageRecipient($message, $currentUser);
                $sender->setMessageRead(true);
                $em->persist($sender);

                $message->addRecipient($sender);
                $em->flush();

                $email = $messageService->createEmail($emailAddresses,
                                                      $message->getSubject(),
                                                      $this->renderView('ImpetusAppBundle:Message:email.html.twig', array('message' => $message)));
                $messageService->send($email);

                $this->get('session')->setFlash('notice', 'Your message was sent!');

                //return new Response($this->renderView('ImpetusAppBundle:Message:email.html.twig', array('message' => $message)));
                return $this->redirect($this->generateUrl('_message_list'));
            }
        }

        return $this->render('ImpetusAppBundle:Pages:message-new.html.twig',
                             array('page' => 'messages',
                                   'form' => $form->createView()
                                   ));
    }

    /**
     * @Route("/recipient/search", name="_message_recipient_search", options={"expose"=true})
     * @Secure(roles="ROLE_PARENT")
     */
    public function recipientSearchAction(Request $request) {
        $doctrine = $this->getDoctrine();

        $users = $doctrine->getRepository('ImpetusAppBundle:User')->findByApproximateName($request->query->get('term'));
        $roles = $doctrine->getRepository('ImpetusAppBundle:Role')->findByApproximateName($request->query->get('term'));

        $json = array();

        if ($users) {
            foreach ($users as $user) {
                array_push($json, array('id' => $user['id'],
                                        'label' => $user['value'],
                                        'value' => $user['value'],
                                        'type' => 'user'));
            }
        }

        if ($roles) {
            foreach ($roles as $role) {
                array_push($json, array('id' => $role->getId(),
                                        'label' => $role->toHumanReadable() . ' ( Group )',
                                        'value' => $role->toHumanReadable(),
                                        'type' => 'role'));
            }
        }

        // Alphabetical sort by value
        usort($json, function($one, $two) {
            return strcmp($one['value'], $two['value']);
        });

        $json = json_encode($json);

        return new Response($json);
    }

    /**
     * @Route("/{id}", name="_message_show", requirements={"id"="\d+"})
     * @Secure(roles="ROLE_PARENT")
     */
    public function showAction($id, Request $request) {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getEntityManager();

        $user = $this->getCurrentUser();

        $parent = $doctrine->getRepository('ImpetusAppBundle:Message')->getParentByIdAndUser($id, $user);

        if (!$parent) {
            throw $this->createNotFoundException('No message found for id ' . $id);
        }

        $reply = new Message();
        $replyForm = $this->createForm(new MessageType(), $reply);

        if ($request->getMethod() == 'POST') {
            $replyForm->bindRequest($request);

            if ($replyForm->isValid()) {
                $messageService = $this->get('message_service');

                $reply->setParent($parent->getMessage());
                $reply->setSender($user);
                $reply->setSubject($parent->getMessage()->getSubject());

                $em->persist($reply);
                $em->flush();

                $emailAddresses = array();

                $recipients = $parent->getMessage()->getRecipients();

                foreach ($recipients as $recipient) {
                    $messageRecipient = new MessageRecipient($reply, $recipient->getUser());
                    $em->persist($messageRecipient);

                    $reply->addRecipient($messageRecipient);

                    $emailAddresses[] = $recipient->getUser()->getEmail();
                }

                $em->flush();

                $email = $messageService->createEmail($emailAddresses, $reply->getSubject(), $reply->getContent());
                $messageService->send($email);

                $this->get('session')->setFlash('notice', 'Your reply was added!');
            }
        }

        $replies = $doctrine->getRepository('ImpetusAppBundle:Message')->getRepliesByParentAndUser($parent->getMessage(), $user);

        // Set replies as read
        $parent->setMessageRead(true);
        foreach ($replies as $replyMsg) {
            $replyMsg->setMessageRead(true);
        }

        $em->flush();

        return $this->render('ImpetusAppBundle:Pages:message-show.html.twig',
                             array('page' => 'message',
                                   'parent' => $parent,
                                   'replies' => $replies,
                                   'replyForm' => $replyForm->createView()
                                   ));
    }
}