<?php

namespace Impetus\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Impetus\AppBundle\Repository\RosterRepository")
 * @ORM\Table(name="roster")
 */
class Roster {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="District")
     */
    protected $district;

    /**
     * @ORM\ManyToOne(targetEntity="Year");
     */
    protected $year;

    /**
     * @ORM\OneToMany(targetEntity="Student", mappedBy="roster")
     */
    protected $students;

    /**
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="roster_assitants")
     */
    protected $assistants;

    /**
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="roster_teachers")
     */
    protected $teachers;

    public function __construct() {
        $this->students = new ArrayCollection();
        $this->assistants = new ArrayCollection();
        $this->teachers = new ArrayCollection();
    }

    public function getYear() {
        return $this->year;
    }

    public function getStudents() {
        return $this->students;
    }

    public function getAssistants() {
        return $this->assistants;
    }

    public function getTeachers() {
        return $this->teachers;
    }
}