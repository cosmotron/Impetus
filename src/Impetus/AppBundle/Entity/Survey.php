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
 * @ORM\Entity(repositoryClass="Impetus\AppBundle\Repository\SurveyRepository")
 * @ORM\Table(name="survey")
 */
class Survey {
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
     * @ORM\Column(type="text", nullable=true)
     */
    protected $instructions;

    /**
     * @ORM\Column(type="string")
     * @Assert\MinLength(1)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="SurveyQuestion", mappedBy="survey", cascade={"all"})
     */
    protected $questions;

    /**
     * @ORM\OneToMany(targetEntity="SurveySubmission", mappedBy="survey", cascade={"all"})
     */
    protected $surveySubmissions;

    /**
     * @ORM\ManyToOne(targetEntity="Year")
     */
    protected $year;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->surveySubmissions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param Impetus\AppBundle\Entity\SurveyQuestion $question
     */
    public function addSurveyQuestion(\Impetus\AppBundle\Entity\SurveyQuestion $question)
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
        foreach ($questions as $surveyQuestion){
            $surveyQuestion->setSurvey($this);
        }
    }

    /**
     * Add surveySubmissions
     *
     * @param Impetus\AppBundle\Entity\SurveySubmission $surveySubmissions
     */
    public function addSurveySubmission(\Impetus\AppBundle\Entity\SurveySubmission $surveySubmissions)
    {
        $this->surveySubmissions[] = $surveySubmissions;
    }

    /**
     * Get surveySubmissions
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getSurveySubmissions()
    {
        return $this->surveySubmissions;
    }

    /**
     * Set surveySubmissions
     *
     */
    public function setSurveySubmissions($surveySubmissions){
        $this->surveySubmissions = $surveySubmissions;
        foreach ($surveySubmissions as $surveySubmission){
            $surveySubmission->setSurvey($this);
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