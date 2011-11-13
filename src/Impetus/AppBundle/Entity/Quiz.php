<?php

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
     * @ORM\ManyToOne(targetEntity="Year")
     */
    protected $year;


    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add questions
     *
     * @param Impetus\AppBundle\Entity\QuizQuestion $questions
     */
    public function addQuizQuestion(\Impetus\AppBundle\Entity\QuizQuestion $questions)
    {
        $this->questions[] = $questions;
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