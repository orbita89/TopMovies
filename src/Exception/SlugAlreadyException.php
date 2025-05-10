<?php

namespace App\Exception;

class SlugAlreadyException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Slug exists');
    }
}
