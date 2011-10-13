<?php

namespace Impetus\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
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
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="district_students",
     *     joinColumns={ @ORM\JoinColumn(name="district_id", referencedColumnName="id") },
     *     inverseJoinColumns={ @ORM\JoinColumn(name="user_id", referencedColumnName="id") }
     * )
     */
    protected $students;

    /**
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="district_teaching_assitants",
     *     joinColumns={ @ORM\JoinColumn(name="district_id", referencedColumnName="id") },
     *     inverseJoinColumns={ @ORM\JoinColumn(name="user_id", referencedColumnName="id") }
     * )
     */
    protected $teachingAssistants;

    /**
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="district_teachers",
     *     joinColumns={ @ORM\JoinColumn(name="district_id", referencedColumnName="id") },
     *     inverseJoinColumns={ @ORM\JoinColumn(name="user_id", referencedColumnName="id") }
     * )
     */
    protected $teachers;

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function __toString() {
        return (string) $this->getName();
    }
}