<?php

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
     * @Secure(roles="ROLE_ADMIN")
     */
    public function listAction() {
        $user = $this->get('security.context')->getToken()->getUser();

        $messages = $this->getDoctrine()->getRepository('ImpetusAppBundle:Message')->getMessageListByRecipient($user);

        // TODO: mark messages as unread when a new reply is posted

        return $this->render('ImpetusAppBundle:Pages:message-list.html.twig',
                             array('page' => 'message',
                                   'messages' => $messages
                                   ));
    }

    /**
     * @Route("/new", name="_message_new", options={"expose"=true})
     * @Secure(roles="ROLE_ADMIN")
     */
    public function newAction(Request $request) {
        $message = new Message();
        $form = $this->createForm(new MessageType(), $message);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $doctrine = $this->getDoctrine();
                $em = $doctrine->getEntityManager();
                $messageService = $this->get('message_service');

                $currentUser = $this->getCurrentUser();

                $message->setSender($currentUser);
                $em->persist($message);
                $em->flush();

                $recipients = json_decode($request->request->get('recipients'));
                $emailAddresses = $messageService->addRecipientsToMessage($recipients, $message);

                $email = $messageService->createEmail($emailAddresses, $message->getSubject(), $message->getContent());
                $messageService->send($email);

                $this->get('session')->setFlash('notice', 'Your message was sent!');
            }
        }

        return $this->render('ImpetusAppBundle:Pages:message-new.html.twig',
                             array('page' => 'messages',
                                   'form' => $form->createView()
                                   ));
    }

    /**
     * @Route("/recipient/search", name="_message_recipient_search", options={"expose"=true})
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
     * @Secure(roles="ROLE_ADMIN")
     */
    public function showAction($id, Request $request) {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getEntityManager();

        $user = $this->getCurrentUser();

        $parent = $doctrine->getRepository('ImpetusAppBundle:Message')->getParentByIdAndUser($id, $user);

        if (!$parent) {
            throw $this->createNotFoundException('No message found for id ' . $id);
        }

        $replies = $doctrine->getRepository('ImpetusAppBundle:Message')->getRepliesByParent($parent);

        // TODO: mark all replies as read too

        $parent->setMessageRead(true);
        $em->flush();

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
                    $messageRecipient = new MessageRecipient();
                    $messageRecipient->setMessage($reply);
                    $messageRecipient->setMessageRead(false);
                    $messageRecipient->setMessageDeleted(false);
                    $messageRecipient->setUser($recipient->getUser());
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

        return $this->render('ImpetusAppBundle:Pages:message-show.html.twig',
                             array('page' => 'message',
                                   'parent' => $parent,
                                   'replies' => $replies,
                                   'replyForm' => $replyForm->createView()
                                   ));
    }
}