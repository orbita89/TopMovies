<?php

namespace App\Tests\ArgumentResolver;

use App\ArgumentResolver\RequestBodyArgumentResolver;
use App\Attribute\MyRequestBody;
use App\Exception\RequestBodyConvertException;
use App\Exception\ValidationException;
use App\Tests\AbstractTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestBodyArgumentResolverTest extends AbstractTestCase
{
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;
    private RequestBodyArgumentResolver $resolver;

    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->resolver = new RequestBodyArgumentResolver($this->serializer, $this->validator);
    }

    public function testResolveWithoutMyRequestBodyAttributeReturnsEmptyArray(): void
    {
        $request = new Request();
        $argument = new ArgumentMetadata('subscriberRequest', 'App\Dto\SubscriberRequest', false, false, null, false, []
        );

        $result = $this->resolver->resolve($request, $argument);

        $this->assertSame([], iterator_to_array($result));
    }

    public function testResolveSuccessfullyDeserializesAndValidates(): void
    {
        $requestData = '{"email":"test@example.com"}';
        $request = new Request([], [], [], [], [], [], $requestData);

        $model = new \stdClass(); // допустим десериализация вернёт объект

        $argument = new ArgumentMetadata(
            'subscriberRequest',
            \stdClass::class,
            false,
            false,
            null,
            false,
            [new MyRequestBody()]
        );

        $this->serializer->method('deserialize')
            ->with($requestData, \stdClass::class, 'json')
            ->willReturn($model);

        $this->validator->method('validate')
            ->with($model)
            ->willReturn(new ConstraintViolationList());

        $result = $this->resolver->resolve($request, $argument);

        $this->assertSame([$model], iterator_to_array($result));
    }

    public function testResolveThrowsRequestBodyConvertExceptionOnDeserializationError(): void
    {
        $requestData = '{"invalid_json":}';
        $request = new Request([], [], [], [], [], [], $requestData);

        $argument = new ArgumentMetadata(
            'subscriberRequest',
            \stdClass::class,
            false,
            false,
            null,
            false,
            [new MyRequestBody()]
        );

        $this->serializer->method('deserialize')
            ->willThrowException(new \Exception('Deserialization error'));

        $this->expectException(RequestBodyConvertException::class);

        iterator_to_array($this->resolver->resolve($request, $argument));
    }

    public function testResolveThrowsValidationExceptionOnValidationErrors(): void
    {
        $requestData = '{"email":"test@example.com"}';
        $request = new Request([], [], [], [], [], [], $requestData);

        $model = new \stdClass();

        $argument = new ArgumentMetadata(
            'subscriberRequest',
            \stdClass::class,
            false,
            false,
            null,
            false,
            [new MyRequestBody()]
        );

        $this->serializer->method('deserialize')
            ->willReturn($model);

        $violation = $this->createMock(ConstraintViolation::class);
        $violations = new ConstraintViolationList([$violation]); // <-- теперь есть ошибка в списке!

        $this->validator->method('validate')
            ->willReturn($violations);

        $this->expectException(ValidationException::class);

        iterator_to_array($this->resolver->resolve($request, $argument));
    }
}
