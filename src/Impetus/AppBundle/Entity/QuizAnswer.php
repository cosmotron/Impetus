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
 * @ORM\Table(name="quiz_answer")
 */
class QuizAnswer {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="correct_answer", type="boolean", nullable=true)
     */
    protected $correctAnswer;

    /**
     * @ORM\Column(type="string")
     */
    protected $label;

    /**
     * @ORM\ManyToOne(targetEntity="QuizQuestion", inversedBy="answers")
     * @ORM\JoinColumn(name="quiz_question_id", referencedColumnName="id")
     */
    protected $question;

    /**
     * @ORM\Column(type="string")
     * @Assert\MinLength(1)
     * @Assert\NotBlank()
     */
    protected $value;


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
     * Set correctAnswer
     *
     * @param boolean $correctAnswer
     */
    public function setCorrectAnswer($correctAnswer)
    {
        $this->correctAnswer = $correctAnswer;
    }

    /**
     * Get correctAnswer
     *
     * @return boolean
     */
    public function getCorrectAnswer()
    {
        return $this->correctAnswer;
    }

    /**
     * Set label
     *
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set value
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set question
     *
     * @param Impetus\AppBundle\Entity\QuizQuestion $question
     */
    public function setQuestion(\Impetus\AppBundle\Entity\QuizQuestion $question)
    {
        $this->question = $question;
    }

    /**
     * Get question
     *
     * @return Impetus\AppBundle\Entity\QuizQuestion
     */
    public function getQuestion()
    {
        return $this->question;
    }
}