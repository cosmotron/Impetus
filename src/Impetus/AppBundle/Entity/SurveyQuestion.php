<?php

namespace Impetus\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="survey_question")
 */
class SurveyQuestion {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="SurveyAnswer", mappedBy="question", cascade={"all"})
     */
    protected $answers;

    /**
     * @ORM\Column(type="text")
     * @Assert\MinLength(1)
     * @Assert\NotBlank()
     */
    protected $text;

    /**
     * @ORM\ManyToOne(targetEntity="Survey", inversedBy="questions")
     */
    protected $survey;

    /**
     * @ORM\Column(type="string")
     */
    protected $type;


    public function __construct()
    {
        $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set text
     *
     * @param text $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Get text
     *
     * @return text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Add answers
     *
     * @param Impetus\AppBundle\Entity\SurveyAnswer $answers
     */
    public function addSurveyAnswer(\Impetus\AppBundle\Entity\SurveyAnswer $answers)
    {
        $this->answers[] = $answers;
    }

    /**
     * Get answers
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Set answers
     *
     */
    public function setAnswers($answers){
        $this->answers = $answers;
        foreach ($answers as $surveyAnswer){
            $surveyAnswer->setQuestion($this);
        }
    }

    /**
     * Set survey
     *
     * @param Impetus\AppBundle\Entity\Survey $survey
     */
    public function setSurvey(\Impetus\AppBundle\Entity\Survey $survey)
    {
        $this->survey = $survey;
    }

    /**
     * Get survey
     *
     * @return Impetus\AppBundle\Entity\Survey
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}