<?php

namespace Impetus\AppBundle\Form;

use Symfony\Component\Validator\Constraints as Assert;
use Impetus\AppBundle\Component\Validator\Constraint\EqualsField;


class RegisterForm
{
    /**
     * @Assert\NotBlank(message = "Please enter a username")
     */
    private $username;

    /**
     * @Assert\NotBlank(message = "Please enter a password")
     */
    private $password;

    /**
     * @Assert\NotBlank(message = "Please enter your password again")
     * @EqualsField("password", message = "The passwords do not match")
     */
    private $confirmPassword;

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setConfirmPassword($confirmPassword)
    {
        $this->confirmPassword = $confirmPassword;
    }

    public function getConfirmPassword()
    {
        return $this->confirmPassword;
    }
}