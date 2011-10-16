<?php

namespace Impetus\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Impetus\AppBundle\Component\Validator\Constraint\EqualsField;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface {
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $college;

    /*
     * @ORM\ManyToOne(targetEntity="District")
     * @ORM\JoinColumn(name="district_id", referencedColumnName="id")
     * @Assert\NotBlank(message="Choose a district")
     * @Assert\Type(type="Impetus\AppBundle\Entity\District", message="District is invalid")
     */
    //protected $district;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Enter an email address")
     * @Assert\Email(message="Enter a valid email address")
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Enter a last name")
     */
    protected $firstName;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Assert\Type(type="bool", message="Graduation status is invalid")
     */
    protected $graduated;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Enter a last name")
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", length=64)
     */
    /*
     * @Assert\NotBlank(message="Enter a password")
     */
    protected $password;

    /*
     * @Assert\NotBlank(message="Enter your password again")
     * @EqualsField("password", message="The passwoords do not match")
     */
    protected $passwordConfirm;

    /**

     */
    protected $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $salt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Enter a username")
     */
    protected $username;

    /**
     * @ORM\ManyToMany(targetEntity="Role")
     * @ORM\JoinTable(
     *     name="user_role",
     *     joinColumns={ @ORM\JoinColumn(name="user_id", referencedColumnName="id") },
     *     inverseJoinColumns={ @ORM\JoinColumn(name="role_id", referencedColumnName="id") }
     * )
     */
    protected $userRoles;

    public function __construct() {
        $this->userRoles = new ArrayCollection();
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

    public function setPasswordConfirm($passwordConfirm) {
        $this->passwordConfirm = $passwordConfirm;
    }

    public function getPasswordConfirm() {
        return $this->passwordConfirm;
    }

    public function setSalt($salt) {
        $this->salt = $salt;
    }

    public function getSalt() {
        return $this->salt;
    }

    public function addRole(\Impetus\AppBundle\Entity\Role $userRoles)
    {
        $this->userRoles[] = $userRoles;
    }

    public function setUserRoles($userRoles)
    {
        $this->userRoles[] = $userRoles;
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

    /**
     * Set college
     *
     * @param string $college
     */
    public function setCollege($college)
    {
        $this->college = $college;
    }

    /**
     * Get college
     *
     * @return string
     */
    public function getCollege()
    {
        return $this->college;
    }

    /*
    public function setDistrict(\Impetus\AppBundle\Entity\District $district)
    {
        $this->district = $district;
    }

    public function getDistrict()
    {
        return $this->district;
    }
    */

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set graduated
     *
     * @param boolean $graduated
     */
    public function setGraduated($graduated)
    {
        $this->graduated = $graduated;
    }

    /**
     * Get graduated
     *
     * @return boolean
     */
    public function getGraduated()
    {
        return $this->graduated;
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
     * Set lastName
     *
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }
}