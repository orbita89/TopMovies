<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class SubscriberAlreadyException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Subscriber email exists');
    }
}

