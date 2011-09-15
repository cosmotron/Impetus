<?php

namespace Impetus\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;


/**
 * @ORM\Entity
 * @ORM\Table(name="role")
 */
class Role implements RoleInterface {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt;

    public function __construct() {
        $this->createdAt = new \DateTime();
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $name;
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt() {
        return $createdAt;
    }

    public function getRole() {
        return $this->getName();
    }
}