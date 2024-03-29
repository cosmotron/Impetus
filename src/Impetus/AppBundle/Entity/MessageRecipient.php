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
 * @ORM\Table(name="message_recipient")
 */
class MessageRecipient {
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Message", inversedBy="recipients")
     */
    protected $message;

    /**
     * @ORM\Column(name="message_deleted", type="boolean")
     */
    protected $messageDeleted;

    /**
     * @ORM\Column(name="message_read", type="boolean")
     */
    protected $messageRead;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $user;


    public function __construct($message, $user) {
        $this->message = $message;
        $this->messageRead = false;
        $this->messageDeleted = false;
        $this->user = $user;
    }

    /**
     * Set messageDeleted
     *
     * @param boolean $messageDeleted
     */
    public function setMessageDeleted($messageDeleted)
    {
        $this->messageDeleted = $messageDeleted;
    }

    /**
     * Get messageDeleted
     *
     * @return boolean
     */
    public function getMessageDeleted()
    {
        return $this->messageDeleted;
    }

    /**
     * Set messageRead
     *
     * @param boolean $messageRead
     */
    public function setMessageRead($messageRead)
    {
        $this->messageRead = $messageRead;
    }

    /**
     * Get messageRead
     *
     * @return boolean
     */
    public function getMessageRead()
    {
        return $this->messageRead;
    }

    /**
     * Set message
     *
     * @param Impetus\AppBundle\Entity\Message $message
     */
    public function setMessage(\Impetus\AppBundle\Entity\Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get message
     *
     * @return Impetus\AppBundle\Entity\Message
     */
    public function getMessage()
    {
        return $this->message;
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