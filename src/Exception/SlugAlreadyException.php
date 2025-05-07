<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class SlugAlreadyException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Slug exists');
    }
}
