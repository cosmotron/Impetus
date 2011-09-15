<?php

namespace Impetus\AppBundle\Form;

use Symfony\Component\Validator\Constraints as Assert;


class LoginForm
{
    /**
     * @Assert\NotBlank(message = "Please enter a username")
     */
    private $username;

    /**
     * @Assert\NotBlank(message = "Please enter a password")
     */
    private $password;

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
}