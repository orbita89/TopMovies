<?php

namespace App\ArgumentResolver;

use App\Attribute\MyRequestBody;
use App\Exception\RequestBodyConvertException;
use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class RequestBodyArgumentResolver implements ValueResolverInterface
{

    public function __construct(private SerializerInterface $serializer, private ValidatorInterface $validatorInterface)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (count($argument->getAttributes(MyRequestBody::class, ArgumentMetadata::IS_INSTANCEOF)) === 0) {
            return [];
        }

        try {
            $model = $this->serializer->deserialize($request->getContent(), $argument->getType(), 'json');
        } catch (Throwable $throwable) {

            throw new RequestBodyConvertException($throwable);
        }


        $errors = $this->validatorInterface->validate($model);
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        yield $model;
    }
}
