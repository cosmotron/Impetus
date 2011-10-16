<?php

namespace Impetus\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Impetus\AppBundle\Repository\DistrictRepository")
 * @ORM\Table(name="district")
 */
class District {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Enter a district name")
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Roster", mappedBy="district")
     */
    protected $rosters;

    public function __construct() {
        $this->rosters = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function getRosters() {
        return $this->rosters;
    }

    public function __toString() {
        return (string) $this->getName();
    }
}