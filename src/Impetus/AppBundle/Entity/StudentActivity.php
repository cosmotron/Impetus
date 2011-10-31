<?php

namespace Impetus\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="student_activity")
 */
class StudentActivity {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Activity")
     */
    protected $activity;

    /**
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="activities")
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
     * Set activity
     *
     * @param Impetus\AppBundle\Entity\Activity $activity
     */
    public function setActivity(\Impetus\AppBundle\Entity\Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * Get activity
     *
     * @return Impetus\AppBundle\Entity\Activity
     */
    public function getActivity()
    {
        return $this->activity;
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