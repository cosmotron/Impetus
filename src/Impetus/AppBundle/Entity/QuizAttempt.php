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
 * @ORM\Entity
 * @ORM\Table(name="quiz_attempt")
 */
class QuizAttempt {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Quiz", inversedBy="quizAttempts")
     */
    protected $quiz;

    /**
     * @ORM\OneToMany(targetEntity="QuizResult", mappedBy="quizAttempt", cascade={"all"})
     */
    protected $quizResults;

    /**
     * @ORM\Column(type="datetime", name="submitted_at")
     */
    protected $submittedAt;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $user;


    public function __construct($quiz, $user) {
        $this->quiz = $quiz;
        $this->quizResults = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set quiz
     *
     * @param Impetus\AppBundle\Entity\Quiz $quiz
     */
    public function setQuiz(\Impetus\AppBundle\Entity\Quiz $quiz)
    {
        $this->quiz = $quiz;
    }

    /**
     * Get quiz
     *
     * @return Impetus\AppBundle\Entity\Quiz
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * Add quizResults
     *
     * @param Impetus\AppBundle\Entity\QuizResult $quizResults
     */
    public function addQuizResult(\Impetus\AppBundle\Entity\QuizResult $quizResults)
    {
        $this->quizResults[] = $quizResults;
    }

    /**
     * Get quizResults
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getQuizResults()
    {
        return $this->quizResults;
    }

    /**
     * Set quizResults
     *
     */
    public function setQuizResults($quizResults){
        $this->quizResults = $quizResults;
        foreach ($quizResults as $quizResult){
            $quizResult->setQuizAttempt($this);
        }
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