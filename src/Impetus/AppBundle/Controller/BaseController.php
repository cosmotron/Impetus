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

use Impetus\AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Exception\AclNotFoundException;
use Symfony\Component\Security\Acl\Exception\InvalidDomainObjectException;
use Symfony\Component\Security\Acl\Model\DomainObjectInterface;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;


class BaseController extends Controller {
    protected function getAuthorizedRosters() {
        $doctrine = $this->getDoctrine();
        $user = $this->getCurrentUser();
        $year = $this->get('year_service')->getCurrentAcademicYear();

        if ($this->hasAdminAuthority()) {
            $rosters = $doctrine->getRepository('ImpetusAppBundle:Roster')->findByYear($year);
        }
        else if ($this->hasTeacherAuthority()) {
            $rosters = $doctrine->getRepository('ImpetusAppBundle:Roster')->findByTeacherAndYear($user, $year);
        }
        else if ($this->hasAssistantAuthority()) {
            $rosters = $doctrine->getRepository('ImpetusAppBundle:Roster')->findByTAAndYear($user, $year);
        }
        else {
            return null;
        }

        return $rosters;
    }

    protected function getCurrentUser() {
        return $user = $this->get('security.context')->getToken()->getUser();
    }

    protected function hasAdminAuthority() {
        $securityContext = $this->get('security.context');

        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }

    protected function hasAssistantAuthority() {
        $securityContext = $this->get('security.context');

        if ($this->get('security.context')->isGranted('ROLE_TA') ||
            $this->get('security.context')->isGranted('ROLE_MENTOR')) {
            return true;
        }

        return false;
    }

    protected function hasTeacherAuthority() {
        $securityContext = $this->get('security.context');

        if ($this->get('security.context')->isGranted('ROLE_TEACHER')) {
            return true;
        }

        return false;
    }
}