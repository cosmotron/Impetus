<?php

namespace Impetus\AppBundle\EventListener;

use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;


class ImpetusControllerListener {
    public function onCoreController(FilterControllerEvent $event) {
        $_controller = $event->getController();

        if (isset($_controller[0]) && ($_controller[0] instanceof BaseController)) {
            $session = $_controller[0]->get('session');

            // If academic_year doesn't exist yet, set it. Otherwise, leave it alone.
            if (!$session->has('academic_year')) {
                $session->set('academic_year', date('Y'));
            }
        }
    }
}