<?php

namespace Impetus\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="path_data")
 */
class PathData {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="text")
     */
    protected $standard;

    /**
     * @ORM\Column(type="text")
     */
    protected $videos;

    /**
     * @ORM\Column(type="text")
     */
    protected $quizzes;


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
     * Set standard
     *
     * @param text $standard
     */
    public function setStandard($standard)
    {
        $this->standard = $standard;
    }

    /**
     * Get standard
     *
     * @return text
     */
    public function getStandard()
    {
        return $this->standard;
    }

    /**
     * Set videos
     *
     * @param text $videos
     */
    public function setVideos($videos)
    {
        $this->videos = $videos;
    }

    /**
     * Get videos
     *
     * @return text
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * Set quizzes
     *
     * @param text $quizzes
     */
    public function setQuizzes($quizzes)
    {
        $this->quizzes = $quizzes;
    }

    /**
     * Get quizzes
     *
     * @return text
     */
    public function getQuizzes()
    {
        return $this->quizzes;
    }
}