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
use Impetus\AppBundle\Entity\User;


class DistrictRepository extends EntityRepository {
    public function findAllOrderedByName() {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT u
                                   FROM ImpetusAppBundle:District u
                                   ORDER BY u.name ASC");

        return $query->getResult();
    }
}