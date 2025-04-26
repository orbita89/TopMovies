<?php

namespace App\Tests\Listener;

use App\Exception\ValidationException;
use App\Listener\ValidationExceptionListener;
use App\Model\ErrorResponse;
use App\Tests\AbstractTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidationExceptionListenerTest extends AbstractTestCase
{

    public function testDoesNothingIfNotValidationException(): void
    {
        $serializer = $this->createMock(SerializerInterface::class);

        $listener = new ValidationExceptionListener($serializer);

        $kernel = $this->createMock(HttpKernelInterface::class);
        $exception = new \RuntimeException('Some error');
        $request = new Request();

        $event = new ExceptionEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST, $exception);

        $listener($event);

        $this->assertNull($event->getResponse());
    }

    public function testSetsResponseOnValidationException(): void
    {
        $serializer = $this->createMock(SerializerInterface::class);

        $violations = new ConstraintViolationList();
        $exception = new ValidationException($violations);

        $serializer->expects($this->once())
            ->method('serialize')
            ->with(
                $this->isInstanceOf(ErrorResponse::class),
                'json'
            )
            ->willReturn('{"error":"Validation failed"}');

        $listener = new ValidationExceptionListener($serializer);

        $kernel = $this->createMock(HttpKernelInterface::class);
        $request = new Request();

        $event = new ExceptionEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST, $exception);

        $listener($event);

        $response = $event->getResponse();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString('{"error":"Validation failed"}', $response->getContent());
    }
}
