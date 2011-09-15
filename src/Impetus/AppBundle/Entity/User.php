<?php

namespace Impetus\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $salt;

    /**
     * @ORM\ManyToMany(targetEntity="Role")
     * @ORM\JoinTable(
     *     name="user_role",
     *     joinColumns={ @ORM\JoinColumn(name="user_id", referencedColumnName="id") },
     *     inverseJoinColumns={ @ORM\JoinColumn(name="role_id", referencedColumnName="id") }
     * )
     */
    private $userRoles;

    public function __construct() {
        $this->userRoles = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setSalt($salt) {
        $this->salt = $salt;
    }

    public function getSalt() {
        return $this->salt;
    }

    public function setUserRoles($userRoles) {
        $this->userRoles = $userRoles;
    }

    public function getUserRoles() {
        return $this->userRoles;
    }

    public function eraseCredentials() {
        ;
    }

    public function getRoles() {
        return $this->getUserRoles()->toArray();
    }

    public function equals(UserInterface $user) {
        return md5($this->getUsername()) == md5($user->getUsername());
    }
}