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
 * @ORM\Table(name="survey_answer")
 */
class SurveyAnswer {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $label;

    /**
     * @ORM\ManyToOne(targetEntity="SurveyQuestion", inversedBy="answers")
     * @ORM\JoinColumn(name="survey_question_id", referencedColumnName="id")
     */
    protected $question;


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
     * Set question
     *
     * @param Impetus\AppBundle\Entity\SurveyQuestion $question
     */
    public function setQuestion(\Impetus\AppBundle\Entity\SurveyQuestion $question)
    {
        $this->question = $question;
    }

    /**
     * Get question
     *
     * @return Impetus\AppBundle\Entity\SurveyQuestion
     */
    public function getQuestion()
    {
        return $this->question;
    }
}