<?php

namespace Impetus\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="survey_result")
 */
class SurveyResult {
    /**
     * @ORM\Column(type="text")
     */
    protected $answer;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SurveySubmission", inversedBy="surveyResults")
     * @ORM\JoinColumn(name="survey_attempt_id", referencedColumnName="id")
     */
    protected $surveyAttempt;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SurveyQuestion")
     * @ORM\JoinColumn(name="survey_question_id", referencedColumnName="id")
     */
    protected $surveyQuestion;


    public function __construct($answer, $surveySubmission, $surveyQuestion) {
        $this->answer = $answer;
        $this->surveyAttempt = $surveySubmission;
        $this->surveyQuestion = $surveyQuestion;
    }

    /**
     * Set answer
     *
     * @param text $answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    /**
     * Get answer
     *
     * @return text
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set surveyAttempt
     *
     * @param Impetus\AppBundle\Entity\SurveySubmission $surveyAttempt
     */
    public function setSurveyAttempt(\Impetus\AppBundle\Entity\SurveySubmission $surveyAttempt)
    {
        $this->surveyAttempt = $surveyAttempt;
    }

    /**
     * Get surveyAttempt
     *
     * @return Impetus\AppBundle\Entity\SurveySubmission
     */
    public function getSurveyAttempt()
    {
        return $this->surveyAttempt;
    }

    /**
     * Set surveyQuestion
     *
     * @param Impetus\AppBundle\Entity\SurveyQuestion $surveyQuestion
     */
    public function setSurveyQuestion(\Impetus\AppBundle\Entity\SurveyQuestion $surveyQuestion)
    {
        $this->surveyQuestion = $surveyQuestion;
    }

    /**
     * Get surveyQuestion
     *
     * @return Impetus\AppBundle\Entity\SurveyQuestion
     */
    public function getSurveyQuestion()
    {
        return $this->surveyQuestion;
    }
}