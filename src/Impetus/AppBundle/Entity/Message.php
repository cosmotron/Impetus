<?php

namespace Impetus\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Impetus\AppBundle\Repository\MessageRepository")
 * @ORM\Table(name="message")
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
     * @Assert\MinLength(1)
     * @Assert\NotBlank()
     */
    protected $content;

    /**
     * @ORM\ManyToOne(targetEntity="Message")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="MessageRecipient", mappedBy="message", cascade={"delete"})
     */
    protected $recipients;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $sender;

    /**
     * @ORM\Column(type="datetime", name="sent_at")
     */
    protected $sentAt;

    /**
     * @ORM\Column(type="string")
     * @Assert\MinLength(1)
     * @Assert\NotBlank()
     */
    protected $subject;


    public function __construct()
    {
        $this->recipients = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sentAt = new \DateTime();
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
     * Set content
     *
     * @param text $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return text
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set sentAt
     *
     * @param datetime $sentAt
     */
    public function setSentAt($sentAt)
    {
        $this->sentAt = $sentAt;
    }

    /**
     * Get sentAt
     *
     * @return datetime
     */
    public function getSentAt()
    {
        return $this->sentAt;
    }

    /**
     * Set subject
     *
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set parent
     *
     * @param Impetus\AppBundle\Entity\Message $parent
     */
    public function setParent(\Impetus\AppBundle\Entity\Message $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return Impetus\AppBundle\Entity\Message
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add recipients
     *
     * @param Impetus\AppBundle\Entity\MessageRecipient $recipients
     */
    public function addRecipient(\Impetus\AppBundle\Entity\MessageRecipient $recipients)
    {
        $this->recipients[] = $recipients;
    }

    /**
     * Get recipients
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * Set sender
     *
     * @param Impetus\AppBundle\Entity\User $sender
     */
    public function setSender(\Impetus\AppBundle\Entity\User $sender)
    {
        $this->sender = $sender;
    }

    /**
     * Get sender
     *
     * @return Impetus\AppBundle\Entity\User
     */
    public function getSender()
    {
        return $this->sender;
    }
}