<?php

namespace Impetus\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="quiz_result")
 */
class QuizResult {
    /**
     * @ORM\Column(type="string")
     */
    protected $answer;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $correct;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="QuizAttempt", inversedBy="quizResults")
     * @ORM\JoinColumn(name="quiz_attempt_id", referencedColumnName="id")
     */
    protected $quizAttempt;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="QuizQuestion")
     * @ORM\JoinColumn(name="quiz_question_id", referencedColumnName="id")
     */
    protected $quizQuestion;


    public function __construct($answer, $correct, $quizAttempt, $quizQuestion) {
        $this->answer = $answer;
        $this->correct = $correct;
        $this->quizAttempt = $quizAttempt;
        $this->quizQuestion = $quizQuestion;
    }

    /**
     * Set answer
     *
     * @param string $answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    /**
     * Get answer
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set correct
     *
     * @param boolean $correct
     */
    public function setCorrect($correct)
    {
        $this->correct = $correct;
    }

    /**
     * Get correct
     *
     * @return boolean
     */
    public function getCorrect()
    {
        return $this->correct;
    }

    /**
     * Set quizAttempt
     *
     * @param Impetus\AppBundle\Entity\QuizAttempt $quizAttempt
     */
    public function setQuizAttempt(\Impetus\AppBundle\Entity\QuizAttempt $quizAttempt)
    {
        $this->quizAttempt = $quizAttempt;
    }

    /**
     * Get quizAttempt
     *
     * @return Impetus\AppBundle\Entity\QuizAttempt
     */
    public function getQuizAttempt()
    {
        return $this->quizAttempt;
    }

    /**
     * Set quizQuestion
     *
     * @param Impetus\AppBundle\Entity\QuizQuestion $quizQuestion
     */
    public function setQuizQuestion(\Impetus\AppBundle\Entity\QuizQuestion $quizQuestion)
    {
        $this->quizQuestion = $quizQuestion;
    }

    /**
     * Get quizQuestion
     *
     * @return Impetus\AppBundle\Entity\QuizQuestion
     */
    public function getQuizQuestion()
    {
        return $this->quizQuestion;
    }
}