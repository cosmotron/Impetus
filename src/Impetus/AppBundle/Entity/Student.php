<?php

namespace Impetus\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="roster_students")
 */
class Student {
    /**
     * @ORM\Column(type="integer")
     */
    protected $grade;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Roster")
     */
    protected $roster;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $user;

    public function getGrade() {
        return $this->grade;
    }

    public function setGrade($grade) {
        $this->grade = $grade;
    }

    public function getRoster() {
        return $this->roster;
    }

    public function getUser() {
        return $this->user;
    }
}