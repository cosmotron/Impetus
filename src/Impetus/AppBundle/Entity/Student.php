<?php

namespace Impetus\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="Impetus\AppBundle\Repository\StudentRepository")
 * @ORM\Table(name="roster_students")
 */
class Student {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="StudentActivity", mappedBy="student", cascade={"all"})
     */
    protected $activities;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $college;

    /**
     * @ORM\OneToMany(targetEntity="Course", mappedBy="student")
     */
    protected $courses;

    /**
     * @ORM\OneToMany(targetEntity="Exam", mappedBy="student")
     */
    protected $exams;

    /**
     * @ORM\Column(type="integer")
     */
    protected $grade;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $graduated;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $major;

    /**
     * @ORM\ManyToOne(targetEntity="Roster")
     */
    protected $roster;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $user;


    public function __construct()
    {
        $this->activities = new \Doctrine\Common\Collections\ArrayCollection();
        $this->courses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->exams = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set college
     *
     * @param string $college
     */
    public function setCollege($college)
    {
        $this->college = $college;
    }

    /**
     * Get college
     *
     * @return string
     */
    public function getCollege()
    {
        return $this->college;
    }

    /**
     * Set grade
     *
     * @param integer $grade
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;
    }

    /**
     * Get grade
     *
     * @return integer
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set graduated
     *
     * @param integer $graduated
     */
    public function setGraduated($graduated)
    {
        $this->graduated = $graduated;
    }

    /**
     * Get graduated
     *
     * @return integer
     */
    public function getGraduated()
    {
        return $this->graduated;
    }

    /**
     * Set major
     *
     * @param string $major
     */
    public function setMajor($major)
    {
        $this->major = $major;
    }

    /**
     * Get major
     *
     * @return string
     */
    public function getMajor()
    {
        return $this->major;
    }

    /**
     * Add activities
     *
     * @param Impetus\AppBundle\Entity\StudentActivity $activities
     */
    public function addActivities(\Impetus\AppBundle\Entity\StudentActivity $activities)
    {
        $this->activities[] = $activities;
        $activities->setStudent($this);
    }

    /**
     * Get activities
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getActivities()
    {
        return $this->activities;
    }

    public function setActivities($activities){
        $this->activities = $activities;
        foreach ($activities as $studentActivity){
            $studentActivity->setStudent($this);
        }
    }

    /**
     * Add courses
     *
     * @param Impetus\AppBundle\Entity\Course $courses
     */
    public function addCourse(\Impetus\AppBundle\Entity\Course $courses)
    {
        $this->courses[] = $courses;
    }

    /**
     * Get courses
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getCourses()
    {
        return $this->courses;
    }

    /**
     * Add exams
     *
     * @param Impetus\AppBundle\Entity\Exam $exams
     */
    public function addExam(\Impetus\AppBundle\Entity\Exam $exams)
    {
        $this->exams[] = $exams;
    }

    /**
     * Get exams
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getExams()
    {
        return $this->exams;
    }

    /**
     * Set roster
     *
     * @param Impetus\AppBundle\Entity\Roster $roster
     */
    public function setRoster(\Impetus\AppBundle\Entity\Roster $roster)
    {
        $this->roster = $roster;
    }

    /**
     * Get roster
     *
     * @return Impetus\AppBundle\Entity\Roster
     */
    public function getRoster()
    {
        return $this->roster;
    }

    /**
     * Set user
     *
     * @param Impetus\AppBundle\Entity\User $user
     */
    public function setUser(\Impetus\AppBundle\Entity\User $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return Impetus\AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}