<?php

namespace App\ArgumentResolver;

use App\Attribute\MyRequestFile;
use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestFileArgumentResolver implements ValueResolverInterface
{
    public function __construct(private ValidatorInterface $validatorInterface)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (0 === count($argument->getAttributes(MyRequestFile::class, ArgumentMetadata::IS_INSTANCEOF))) {
            return [];
        }

        /** @var MyRequestFile $attribute */
        $attribute = $argument->getAttributes(MyRequestFile::class, ArgumentMetadata::IS_INSTANCEOF)[0];

        /** @var UploadedFile $uploadFile */
        $uploadFile = $request->files->get($attribute->getField());

        $errors = $this->validatorInterface->validate($uploadFile, $attribute->getConstraints());

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        yield $uploadFile;
    }
}
