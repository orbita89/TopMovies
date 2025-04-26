<?php

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends \RuntimeException
{
    public function __construct(private ConstraintViolationListInterface $violations)
    {
        parent::__construct('Invalid request body');
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}
