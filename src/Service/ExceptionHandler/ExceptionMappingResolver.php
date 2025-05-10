<?php

namespace App\Service\ExceptionHandler;

class ExceptionMappingResolver
{
    private array $mappings;

    public function __construct(array $mappings)
    {
        foreach ($mappings as $class => $mapping) {
            if (empty($mapping['code'])) {
                throw new \InvalidArgumentException('Exception mapping must have a code');
            }

            $this->getMapping(
                class: $class,
                code: $mapping['code'],
                hidden: $mapping['hidden'] ?? true,
                loggable: $mapping['loggable'] ?? false
            );
        }
    }

    public function resolve(string $throwableClass): ?ExceptionMapping
    {
        $foundMapping = null;

        foreach ($this->mappings as $class => $mapping) {
            if ($throwableClass === $class || is_subclass_of($throwableClass, $class)) {
                $foundMapping = $mapping;
                break;
            }
        }

        return $foundMapping;
    }

    public function getMapping(string $class, int $code, bool $hidden, bool $loggable): void
    {
        $this->mappings[$class] = new ExceptionMapping(
            code: $code,
            hidden: $hidden,
            loggable: $loggable
        );
    }
}
