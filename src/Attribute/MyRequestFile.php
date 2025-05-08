<?php

namespace App\Attribute;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PARAMETER)]
class MyRequestFile
{
    public function __construct(private string $field, private array $constraints = [])
    {
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function setField(string $field): MyRequestFile
    {
        $this->field = $field;
        return $this;
    }

    /**
     * @return Constraint[]
     */
    public function getConstraints(): array
    {
        return $this->constraints;
    }

    public function setConstraints(array $constraints): MyRequestFile
    {
        $this->constraints = $constraints;
        return $this;
    }
}
