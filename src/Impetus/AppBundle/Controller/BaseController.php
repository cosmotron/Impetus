<?php

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
    protected function setAcl($entity, $user, $mask = MaskBuilder::MASK_MASTER) {
        $this->getDoctrine()->getEntityManager()->flush();
        $aclProvider = $this->container->get('security.acl.provider');

        $objectIdentity = ObjectIdentity::fromDomainObject($entity);
        $securityIdentity = UserSecurityIdentity::fromAccount($user);

        try {
            $acl = $aclProvider->findAcl($objectIdentity);
        } catch (AclNotFoundException $e) {
            $acl = $aclProvider->createAcl($objectIdentity);
        }

        $acl->insertObjectAce($securityIdentity, $mask);

        $aclProvider->updateAcl($acl);
    }

    protected function checkAdminAuthority() {
        $securityContext = $this->get('security.context');

        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }

    protected function checkDistrictAuthority($checkDistrictId) {
        if ($this->checkAdminAuthority()) {
            return true;
        }

        $em = $this->getDoctrine()->getEntityManager();
        $district = $em->getRepository('ImpetusAppBundle:District')->find($checkDistrictId);

        $districtOI = $this->getDoctrineObjectIdentity($district);

        if (($this->get('security.context')->isGranted('ROLE_TA') ||
             $this->get('security.context')->isGranted('ROLE_MENTOR')) &&
            $this->get('security.context')->isGranted('MASTER', $districtOI)) {
            return true;
        }

        return false;
    }

    protected function getDoctrineObjectIdentity($domainObject) {
        if (!is_object($domainObject)) {
            throw new InvalidDomainObjectException('$domainObject must be an object.');
        }

        try {
            $className = ($domainObject instanceof \Doctrine\ORM\Proxy\Proxy) ? get_parent_class($domainObject) : get_class($domainObject);

            if ($domainObject instanceof DomainObjectInterface) {
                return new ObjectIdentity($domainObject->getObjectIdentifier(), $className);
            }
            else if (method_exists($domainObject, 'getId')) {
                return new ObjectIdentity($domainObject->getId(), $className);
            }
        } catch (\InvalidArgumentException $invalid) {
            throw new InvalidDomainObjectException($invalid->getMessage(), 0, $invalid);
        }

        throw new InvalidDomainObjectException('$domainObject must either implement the DomainObjectInterface, or have a method named "getId".');
    }

    protected function getCurrentUser() {
        return $user = $this->get('security.context')->getToken()->getUser();
    }
}