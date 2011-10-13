<?php

namespace Impetus\AppBundle\DataFIxtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Impetus\AppBundle\Entity\District;
use Impetus\AppBundle\Entity\Grade;
use Impetus\AppBundle\Entity\Role;
use Impetus\AppBundle\Entity\User;
use Impetus\AppBundle\Entity\Year;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class FixtureLoader implements FixtureInterface {
    public function load($manager) {
        // Create the Roles
        $adminRole = $this->createRole('ROLE_ADMIN');
        $manager->persist($adminRole);

        $teacherRole = $this->createRole('ROLE_TEACHER');
        $manager->persist($teacherRole);

        $taRole = $this->createRole('ROLE_TA');
        $manager->persist($taRole);

        $mentorRole = $this->createRole('ROLE_MENTOR');
        $manager->persist($mentorRole);

        $studentRole = $this->createRole('ROLE_STUDENT');
        $manager->persist($studentRole);

        $parentRole = $this->createRole('ROLE_PARENT');
        $manager->persist($parentRole);

        // Create an academic year
        $year = new Year();
        $year->setYear(2011);
        $manager->persist($year);

        // Create a test District
        $district = new District();
        $district->setName('Example Central School District');
        $district2 = new District();
        $district2->setName('Public School District');

        $manager->persist($district);
        $manager->persist($district2);

        // Create an Admin user
        $adminUser = $this->createUser('admin', $adminRole);
        $manager->persist($adminUser);

        // Create a Student user
        $studentUser = $this->createUser('student', $studentRole);
        $manager->persist($studentUser);

        // Place Student in a Grade
        $studentGrade = new Grade();
        $studentGrade->setGrade(11);
        $studentGrade->setUser($studentUser);
        $studentGrade->setYear($year);
        $manager->persist($studentGrade);

        // Write to database
        $manager->flush();
    }

    private function createRole($name) {
        $role = new Role();
        $role->setName($name);
        return $role;
    }

    private function createUser($username, $role) {
        $user = new User();
        $user->setUsername($username);
        $user->setFirstName(ucfirst($username));
        $user->setLastName('Example');
        $user->setEmail($username . '@example.com');
        $user->setSalt(md5(rand()));
        $user->setUserRoles($role);
        //$user->setDistrict($district);

        // Encode and set the password
        $encoder = new MessageDigestPasswordEncoder('sha256', true, 10);
        $password = $encoder->encodePassword('impetus', $user->getSalt());
        $user->setPassword($password);

        return $user;
    }
}