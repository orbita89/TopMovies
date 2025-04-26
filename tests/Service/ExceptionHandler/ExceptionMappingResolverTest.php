<?php

namespace App\Tests\Service\ExceptionHandler;

use App\Service\ExceptionHandler\ExceptionMapping;
use App\Service\ExceptionHandler\ExceptionMappingResolver;
use App\Tests\AbstractTestCase;
use InvalidArgumentException;

class ExceptionMappingResolverTest extends AbstractTestCase
{

    public function testConstructorThrowsExceptionWhenCodeIsMissing(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ExceptionMappingResolver([
            'someClass' => ['hidden' => true],
        ]);
    }

    public function testGetMappingAndResolve(): void
    {
        $resolver = new ExceptionMappingResolver([]);

        $resolver->getMapping(
            \RuntimeException::class,
            code: 400,
            hidden: false,
            loggable: true
        );

        $mapping = $resolver->resolve(\RuntimeException::class);

        $this->assertInstanceOf(ExceptionMapping::class, $mapping);
        $this->assertSame(400, $mapping->getCode());
        $this->assertFalse($mapping->isHidden());
        $this->assertTrue($mapping->isLoggable());
    }

//    public function testResolveUsesInheritance(): void
//    {
//        $resolver = new ExceptionMappingResolver([
//            \RuntimeException::class => ['code' => 500],
//        ]);
//
//        // LogicException наследует от RuntimeException
//        $mapping = $resolver->resolve(\LogicException::class);
//
//        $this->assertInstanceOf(ExceptionMapping::class, $mapping);
//        $this->assertSame(500, $mapping->getCode());
//    }

    public function testResolveReturnsNullIfNotFound(): void
    {
        $resolver = new ExceptionMappingResolver([
            \RuntimeException::class => ['code' => 400],
        ]);

        $mapping = $resolver->resolve(\Exception::class); // не наследник RuntimeException

        $this->assertNull($mapping);
    }
}
