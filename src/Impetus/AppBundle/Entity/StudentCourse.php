<?php

namespace Impetus\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="student_course")
 */
class StudentCourse {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Course")
     */
    protected $course;

    /**
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="courses")
     */
    protected $student;


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
     * Set course
     *
     * @param Impetus\AppBundle\Entity\Course $course
     */
    public function setCourse(\Impetus\AppBundle\Entity\Course $course)
    {
        $this->course = $course;
    }

    /**
     * Get course
     *
     * @return Impetus\AppBundle\Entity\Course
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Set student
     *
     * @param Impetus\AppBundle\Entity\Student $student
     */
    public function setStudent(\Impetus\AppBundle\Entity\Student $student)
    {
        $this->student = $student;
    }

    /**
     * Get student
     *
     * @return Impetus\AppBundle\Entity\Student
     */
    public function getStudent()
    {
        return $this->student;
    }
}