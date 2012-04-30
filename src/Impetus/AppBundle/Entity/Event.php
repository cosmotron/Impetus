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
 * Impetus\AppBundle\Entity\Event
 *
 * @ORM\Entity(repositoryClass="Impetus\AppBundle\Repository\EventRepository")
 * @ORM\Table(name="event")
 */
class Event {
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\MinLength(2)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(name="event_date", type="date")
     * @Assert\Date()
     * @Assert\NotBlank()
     */
    private $eventDate;

    /**
     * @ORM\OneToMany(targetEntity="EventAttendee", mappedBy="event", cascade={"all"})
     */
    protected $attendees;

    public function __construct()
    {
        $this->attendees = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set eventDate
     *
     * @param eventDate $eventDate
     */
    public function setEventDate($eventDate)
    {
        $this->eventDate = $eventDate;
    }

    /**
     * Get eventDate
     *
     * @return eventDate
     */
    public function getEventDate()
    {
        return $this->eventDate;
    }

    /**
     * Add attendee
     *
     * @param Impetus\AppBundle\Entity\EventAttendee $attendees
     */
    public function addEventAttendee(\Impetus\AppBundle\Entity\EventAttendee $attendee)
    {
        $this->attendees[] = $attendee;
    }

    /**
     * Get attendees
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getAttendees()
    {
        return $this->attendees;
    }

    /**
     * Set attendees
     *
     */
    public function setAttendees($attendees){
        $this->attendees = $attendees;
        foreach ($attendees as $eventAttendee) {
            $eventAttendee->setEvent($this);
        }
    }
}