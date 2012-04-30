<?php

/*
 * This file is part of the ImpetusAppBundle
 *
 * (c) Ryan Lewis <lewis.ryan.j@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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