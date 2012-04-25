<?php

namespace Impetus\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="student_exam")
 */
class StudentExam {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Exam")
     */
    protected $exam;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer", message="The score must be an integer")
     * @Assert\Min(limit=0, message="The score must non-negative")
     * @Assert\NotBlank(message="The score must not be blank")
     */
    protected $score;

    /**
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="exams")
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
     * Set exam
     *
     * @param Impetus\AppBundle\Entity\Exam $exam
     */
    public function setExam(\Impetus\AppBundle\Entity\Exam $exam)
    {
        $this->exam = $exam;
    }

    /**
     * Get exam
     *
     * @return Impetus\AppBundle\Entity\Exam
     */
    public function getExam()
    {
        return $this->exam;
    }

    /**
     * Set score
     *
     * @param integer $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }

    /**
     * Get score
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
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