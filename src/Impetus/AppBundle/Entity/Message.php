<?php

namespace Impetus\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Impetus\AppBundle\Repository\StudentRepository")
 * @ORM\Table(name="roster_students")
 */
class Message {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="text")
     */
    protected $content;

    /**
     * @ORM\OneToOne(targetEntity="Message")
     */
    protected $parent;

    /**
     * @ORM\ManyToMany(targetEntity="User)
     * @ORM\JoinTable(name="message_recipients")
     */
    protected $recipients;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $sender;

    /*
     * @ORM\Column(type="string")
     */
    protected $subject;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    protected $createdAt;

    public function __construct() {
        $this->recipients = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    // TODO: add getters/setters
    public function getContent() {
        return $this->content;
    }

    public function getSubject() {
        return $this->subject;
    }
}