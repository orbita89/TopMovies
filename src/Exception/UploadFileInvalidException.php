<?php

namespace App\Exception;

class UploadFileInvalidException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('File type not found');
    }
}
