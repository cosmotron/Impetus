<?php

namespace Impetus\AppBundle\DataFIxtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Impetus\AppBundle\Entity\PathNode;
use Impetus\AppBundle\Entity\Role;
use Impetus\AppBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class FixtureLoader implements FixtureInterface {
    public function load($manager) {
        // Create the Admin role
        $role = new Role();
        $role->setName('ROLE_ADMIN');

        $manager->persist($role);

        // Create the Admin user
        $user = new User();
        $user->setUsername('admin');
        $user->setSalt(md5(rand()));
        $user->getUserRoles()->add($role);

        // Encode and set Admin's password
        $encoder = new MessageDigestPasswordEncoder('sha256', true, 10);
        $password = $encoder->encodePassword('impetus', $user->getSalt());
        $user->setPassword($password);

        $manager->persist($user);

        // Create two path map nodes, one of which is a prereq
        $addition = new PathNode();
        $addition->setName("Addition 1");
        $addition->setHPos(0);
        $addition->setVPos(0);

        $manager->persist($addition);

        $subtraction = new PathNode();
        $subtraction->setName("Subtraction 1");
        $subtraction->setHPos(0.5);
        $subtraction->setVPos(-0.5);
        $subtraction->addPathNode($addition);

        $manager->persist($subtraction);

        // Write to database
        $manager->flush();
    }
}