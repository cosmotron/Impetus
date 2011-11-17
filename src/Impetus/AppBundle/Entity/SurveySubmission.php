<?php

namespace Impetus\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="survey_submission")
 */
class SurveySubmission {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Survey", inversedBy="surveySubmissions")
     */
    protected $survey;

    /**
     * @ORM\OneToMany(targetEntity="SurveyResult", mappedBy="surveyAttempt", cascade={"all"})
     */
    protected $surveyResults;

    /**
     * @ORM\Column(type="datetime", name="submitted_at")
     */
    protected $submittedAt;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $user;


    public function __construct($survey, $user) {
        $this->survey = $survey;
        $this->surveyResults = new \Doctrine\Common\Collections\ArrayCollection();
        $this->submittedAt = new \DateTime();
        $this->user = $user;
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
     * Set submittedAt
     *
     * @param datetime $submittedAt
     */
    public function setSubmittedAt($submittedAt)
    {
        $this->submittedAt = $submittedAt;
    }

    /**
     * Get submittedAt
     *
     * @return datetime 
     */
    public function getSubmittedAt()
    {
        return $this->submittedAt;
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
     * Add surveyResults
     *
     * @param Impetus\AppBundle\Entity\SurveyResult $surveyResults
     */
    public function addSurveyResult(\Impetus\AppBundle\Entity\SurveyResult $surveyResults)
    {
        $this->surveyResults[] = $surveyResults;
    }

    /**
     * Get surveyResults
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSurveyResults()
    {
        return $this->surveyResults;
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