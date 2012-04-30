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
     * @ORM\OneToMany(targetEntity="StudentCourse", mappedBy="student", cascade={"all"})
     */
    protected $courses;

    /**
     * @ORM\OneToMany(targetEntity="StudentExam", mappedBy="student", cascade={"all"})
     */
    protected $exams;

    /**
     * @ORM\Column(type="integer")
     */
    protected $grade;

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

    /**
     * Set activities
     *
     */
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
        $courses->setStudent($this);
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
     * Set courses
     *
     */
    public function setCourses($courses){
        $this->courses = $courses;
        foreach ($courses as $studentCourse){
            $studentCourse->setStudent($this);
        }
    }

    /**
     * Add exams
     *
     * @param Impetus\AppBundle\Entity\Exam $exams
     */
    public function addExam(\Impetus\AppBundle\Entity\Exam $exams)
    {
        $this->exams[] = $exams;
        $exams->setStudent($this);
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
     * Set exams
     *
     */
    public function setExams($exams){
        $this->exams = $exams;
        foreach ($exams as $studentExam){
            $studentExam->setStudent($this);
        }
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