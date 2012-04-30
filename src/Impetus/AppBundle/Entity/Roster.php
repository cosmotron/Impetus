<?php

/*
 * This file is part of the ImpetusAppBundle
 *
 * (c) Ryan Lewis <lewis.ryan.j@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Impetus\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Impetus\AppBundle\Entity\District;
use Impetus\AppBundle\Entity\Student;
use Impetus\AppBundle\Entity\User;
use Impetus\AppBundle\Entity\Year;
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



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set district
     *
     * @param Impetus\AppBundle\Entity\District $district
     */
    public function setDistrict(\Impetus\AppBundle\Entity\District $district)
    {
        $this->district = $district;
    }

    /**
     * Get district
     *
     * @return Impetus\AppBundle\Entity\District
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Set year
     *
     * @param Impetus\AppBundle\Entity\Year $year
     */
    public function setYear(\Impetus\AppBundle\Entity\Year $year)
    {
        $this->year = $year;
    }

    /**
     * Get year
     *
     * @return Impetus\AppBundle\Entity\Year
     */
    public function getYear()
    {
        return $this->year;
    }

    public function addStudent(Student $student) {
        $this->students->add($student);
    }

    public function getStudents() {
        return $this->students;
    }

    public function removeStudent(Student $student) {
        $this->students->removeElement($student);
    }

    public function addAssistant(User $assistant) {
        $this->assistants->add($assistant);
    }

    public function getAssistants() {
        return $this->assistants;
    }

    public function removeAssistant(User $assistant) {
        $this->assistants->removeElement($assistant);
    }

    public function addTeacher(User $teacher) {
        $this->teachers->add($teacher);
    }

    public function getTeachers() {
        return $this->teachers;
    }

    public function removeTeacher(User $teacher) {
        $this->teachers->removeElement($teacher);
    }
}