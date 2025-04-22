<?php

namespace App\Exception;

class InvalidBookCategoryException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Invalid book category');
    }
}
