<?php

namespace Impetus\AppBundle\EventListener;

use Impetus\AppBundle\Entity\Logger;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;


class SecurityListener {
    protected $doctrine;

    protected $security;

    public function __construct($doctrine, SecurityContext $security) {
        $this->doctrine = $doctrine;
        $this->security = $security;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event) {
        $em = $this->doctrine->getEntityManager();

        $logger = new Logger();
        $logger->setEventName('SECURITY.LOGIN');
        $logger->setData($this->security->getToken()->getUser()->getUsername());
        $em->persist($logger);

        $em->flush();
    }
}