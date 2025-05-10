<?php

namespace App\Exception;

class UserAlreadyException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('User already exists');
    }
}
