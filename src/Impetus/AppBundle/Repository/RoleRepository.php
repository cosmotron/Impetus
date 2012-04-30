<?php

/*
 * This file is part of the ImpetusAppBundle
 *
 * (c) Ryan Lewis <lewis.ryan.j@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Impetus\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Impetus\AppBundle\Entity\Role;


class RoleRepository extends EntityRepository {
    public function findByApproximateName($name) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT r
                                   FROM ImpetusAppBundle:Role r
                                   WHERE r.name LIKE :name
                                   ")->setParameter('name', "%".$name."%");

        return $query->getResult();
    }
}