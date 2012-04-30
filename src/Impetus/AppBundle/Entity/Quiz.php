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
 * @ORM\Entity(repositoryClass="Impetus\AppBundle\Repository\QuizRepository")
 * @ORM\Table(name="quiz")
 */
class Quiz {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="text")
     */
    protected $instructions;

    /**
     * @ORM\Column(type="string")
     * @Assert\MinLength(1)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="QuizQuestion", mappedBy="quiz", cascade={"all"})
     */
    protected $questions;

    /**
     * @ORM\OneToMany(targetEntity="QuizAttempt", mappedBy="quiz", cascade={"all"})
     */
    protected $quizAttempts;

    /**
     * @ORM\ManyToOne(targetEntity="Year")
     */
    protected $year;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->quizAttempts = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set instructions
     *
     * @param text $instructions
     */
    public function setInstructions($instructions)
    {
        $this->instructions = $instructions;
    }

    /**
     * Get instructions
     *
     * @return text
     */
    public function getInstructions()
    {
        return $this->instructions;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add question
     *
     * @param Impetus\AppBundle\Entity\QuizQuestion $question
     */
    public function addQuestion(\Impetus\AppBundle\Entity\QuizQuestion $question)
    {
        $this->questions[] = $question;
    }

    /**
     * Get questions
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set questions
     *
     */
    public function setQuestions($questions){
        $this->questions = $questions;
        foreach ($questions as $quizQuestion){
            $quizQuestion->setQuiz($this);
        }
    }

    /**
     * Add quizAttempt
     *
     * @param Impetus\AppBundle\Entity\QuizQuestion $quizAttempt
     */
    public function addQuizAttempt(\Impetus\AppBundle\Entity\QuizAttempt $quizAttempt)
    {
        $this->quizAttempts[] = $quizAttempt;
    }

    /**
     * Get quizAttempts
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getQuizAttempts()
    {
        return $this->quizAttempts;
    }

    /**
     * Set quizAttempts
     *
     */
    public function setQuizAttempts($quizAttempts){
        $this->quizAttempts = $quizAttempts;
        foreach ($quizAttempts as $quizAttempt){
            $quizAttempt->setQuiz($this);
        }
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
}