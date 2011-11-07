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
     * @Route("/new", name="_message_new", options={"expose"=true})
     * @Secure(roles="ROLE_ADMIN")
     */
    public function newAction(Request $request) {
        $message = new Message();
        $form = $this->createForm(new MessageType(), $message);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                //echo '<pre>';
                $doctrine = $this->getDoctrine();

                $roles = json_decode($request->request->get('role-recipients'));
                $users = json_decode($request->request->get('user-recipients'));

                if ($roles) {
                    //echo "== Role Users\n";
                    foreach ($roles as $key => $value) {
                        $roleData = $doctrine->getRepository('ImpetusAppBundle:Role')->find($value);
                        $usersData = $doctrine->getRepository('ImpetusAppBundle:User')->findByUserRole($roleData);

                        foreach ($usersData as $userData) {
                            /*
                            echo '"'
                                . $userData->getFirstName(). ' '
                                . $userData->getLastName() . '": '
                                . $userData->getEmail() . "\n";
                            */

                            $toArray[] = $userData->getEmail();
                        }
                    }
                    echo "\n";
                }

                if ($users) {
                    //echo "== Explicit Users\n";
                    foreach ($users as $key => $value) {
                        $userData = $doctrine->getRepository('ImpetusAppBundle:User')->find($value);
                        /*
                        echo '"'
                            . $userData->getFirstName(). ' '
                            . $userData->getLastName() . '": '
                            . $userData->getEmail() . "\n";
                        */

                        $toArray[] = $userData->getEmail();
                    }
                    //echo "\n";
                }

                //echo "== Subject\n" . $message->getSubject() . "\n\n";
                //echo "== Content\n" . $message->getContent() . "\n";

                //echo '</pre>';

                $swiftMessage = \Swift_Message::newInstance()
                    ->setSubject($message->getSubject())
                    ->setFrom('impetustem@gmail.com')
                    ->setTo($toArray)
                    ->setBody($message->getContent());

                $this->get('mailer')->send($swiftMessage);

                $this->get('session')->setFlash('notice', 'Your message was sent!');
            }
        }

        return $this->render('ImpetusAppBundle:Pages:message-new.html.twig',
                             array('page' => 'messages',
                                   'form' => $form->createView())
                             );
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
        //echo '<pre>';
        //print_r(json_decode($json));
        //echo '</pre>';

        return new Response($json);
    }

    /**
     * @Route("/{id}", name="_message_show", requirements={"id"="\d+"})
     * @Secure(roles="ROLE_ADMIN")
     */
    public function showAction($id, Request $request) {

    }
}