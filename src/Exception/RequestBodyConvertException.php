<?php

namespace App\Exception;

use RuntimeException;
use Throwable;

class RequestBodyConvertException extends RuntimeException
{
    public function __construct(Throwable $throwable)
    {
        parent::__construct(message: 'Invalid request body' , code: 0, previous: $throwable);
    }
}
