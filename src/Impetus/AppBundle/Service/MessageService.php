<?php

namespace Impetus\AppBundle\Service;

use Impetus\AppBundle\Entity\Message;
use Impetus\AppBundle\Entity\MessageRecipient;


class MessageService {
    private $doctrine;
    private $mailer;

    public function __construct($doctrine, $mailer) {
        $this->doctrine = $doctrine;
        $this->mailer = $mailer;
    }

    public function addRecipientsToMessage($recipients, Message &$message) {
        $em = $this->doctrine->getEntityManager();
        $emails = array();
        //echo '<pre>';

        foreach ($recipients as $recipient) {
            switch ($recipient->type) {
                case 'role':
                    $role = $this->doctrine->getRepository('ImpetusAppBundle:Role')->find($recipient->id);
                    $users = $this->doctrine->getRepository('ImpetusAppBundle:User')->findByUserRole($role);

                    foreach ($users as $user) {
                        $messageRecipient = new MessageRecipient($message, $user);
                        $em->persist($messageRecipient);

                        $message->addRecipient($messageRecipient);

                        $emails[] = $user->getEmail();
                    }
                    break;

                case 'user':
                    $user = $this->doctrine->getRepository('ImpetusAppBundle:User')->find($recipient->id);

                    $messageRecipient = new MessageRecipient($message, $user);
                    $em->persist($messageRecipient);

                    $message->addRecipient($messageRecipient);

                    $emails[] = $user->getEmail();
                    break;
            }
        }

        $em->flush();

        //echo '</pre>';
        return $emails;
    }

    public function createEmail($emailAddresses, $subject, $body) {
        $swiftMessage = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('impetustem@gmail.com')
            ->setTo($emailAddresses)
            ->setBody($body);

        return $swiftMessage;
    }

    public function send(\Swift_Message $message) {
        $this->mailer->send($message);
    }
}