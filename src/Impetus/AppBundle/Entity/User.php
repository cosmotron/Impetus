<?php

namespace Impetus\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="Impetus\AppBundle\Repository\UserRepository")
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\MinLength(6)
     * @Assert\NotBlank(message="Enter a password")
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $salt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\MinLength(2)
     * @Assert\NotBlank()
     */
    protected $username;

    /**
     * @ORM\ManyToMany(targetEntity="Role")
     * @Assert\NotBlank()
     */
    protected $userRoles;

    // Extra
    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Date()
     */
    protected $birthday;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    protected $email;

   /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $ethnicity;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\MinLength(2)
     * @Assert\NotBlank()
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=6, nullable=true)
     * @Assert\Choice(choices = {"", "male", "female"})
     */
    protected $gender;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\MinLength(2)
     * @Assert\NotBlank()
     */
    protected $lastName;

    public function __construct() {
        $this->userRoles = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getRoles() {
        return $this->getUserRoles()->toArray();
    }

    public function getSalt() {
        return $this->salt;
    }

    public function setSalt($salt) {
        $this->salt = $salt;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getUserRoles() {
        return $this->userRoles;
    }

    public function setUserRoles($userRoles) {
        $this->userRoles[] = $userRoles;
    }

    // Extra
    public function getBirthday() {
        return $this->birthday;
    }

    public function setBirthday($birthday) {
        $this->birthday = $birthday;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getEthnicity() {
        return $this->ethnicity;
    }

    public function setEthnicity($ethnicity) {
        $this->ethnicity = $ethnicity;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function getGender() {
        return $this->gender;
    }

    public function setGender($gender) {
        $this->gender = $gender;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function equals(UserInterface $user) {
        return md5($this->getUsername()) == md5($user->getUsername());
    }

    public function eraseCredentials() {
        ;
    }
}