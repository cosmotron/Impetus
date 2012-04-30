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

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;


/**
 * @ORM\Entity(repositoryClass="Impetus\AppBundle\Repository\RoleRepository")
 * @ORM\Table(name="role")
 */
class Role implements RoleInterface {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    protected $createdAt;

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
        return $this->name;
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getRole() {
        return $this->getName();
    }

    public function toHumanReadable() {
        return ucfirst(strtolower(substr($this->getName(), 5)));
    }

    public function __toString() {
        return (string) $this->getName();
    }
}