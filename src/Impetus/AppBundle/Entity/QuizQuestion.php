<?php

namespace Impetus\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="quiz_question")
 */
class QuizQuestion {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="QuizAnswer", mappedBy="question", cascade={"all"})
     */
    protected $answers;

    /**
     * @ORM\Column(type="text")
     * @Assert\MinLength(1)
     * @Assert\NotBlank()
     */
    protected $text;

    /**
     * @ORM\ManyToOne(targetEntity="Quiz", inversedBy="questions")
     */
    protected $quiz;


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
     * @param Impetus\AppBundle\Entity\QuizAnswer $answers
     */
    public function addAnswers(\Impetus\AppBundle\Entity\QuizAnswer $answers)
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
        foreach ($answers as $quizAnswer){
            $quizAnswer->setQuestion($this);
        }
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
}