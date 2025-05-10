<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SignUpRequest
{
    #[NotBlank]
    private string $firstName;
    #[NotBlank]
    private string $lastName;
    #[NotBlank]
    #[Email]
    public string $email;
    #[NotBlank]
    #[Length(min: 8)]
    private string $password;
    #[NotBlank]
    #[EqualTo(propertyPath: 'password', message: 'Passwords do not match')]
    #[Length(min: 8)]
    private string $confirmPassword;

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): SignUpRequest
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): SignUpRequest
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): SignUpRequest
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): SignUpRequest
    {
        $this->password = $password;

        return $this;
    }

    public function getConfirmPassword(): string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): SignUpRequest
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}
