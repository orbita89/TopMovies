<?php

namespace App\Exception;

class SubscriberAlreadyException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Subscriber email exists');
    }
}
