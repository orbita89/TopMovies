<?php

namespace App\Listener;

use App\Model\ErrorResponse;
use App\Service\ExceptionHandler\ExceptionMapping;
use App\Service\ExceptionHandler\ExceptionMappingResolver;
use App\Service\Recommendation\Exception\AccessDeniedException;
use MongoDB\Driver\Exception\AuthenticationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\SerializerInterface;

class ApiExceptionListener
{
    public function __construct(
        private ExceptionMappingResolver $exceptionMappingResolver,
        private LoggerInterface $logger,
        private SerializerInterface $serializer,
        private bool $isDebug,
    ) {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();
        //        if ($this->isSecondaryException($throwable)) {
        //            return;
        //        }
        $mapping = $this->exceptionMappingResolver->resolve(get_class($throwable));

        if (null === $mapping) {
            $mapping = ExceptionMapping::fromCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($mapping->getCode() >= Response::HTTP_INTERNAL_SERVER_ERROR || $mapping->isLoggable()) {
            $this->logger->error(
                $throwable->getMessage(),
                [
                    'trace' => $throwable->getTraceAsString(),
                    'previous' => null !== $throwable->getPrevious() ? $throwable->getPrevious()->getMessage() : '',
                ]
            );
        }

        $message = $mapping->isHidden() ? Response::$statusTexts[$mapping->getCode()] : $throwable->getMessage();
        $details = $this->isDebug ? ['trace' => $throwable->getTraceAsString()] : null;
        $data = $this->serializer->serialize(new ErrorResponse($message, $details), 'json');

        $event->setResponse(new JsonResponse($data, $mapping->getCode(), [], true));
    }

    private function isSecondaryException(\Throwable $throwable): bool
    {
        return $throwable instanceof AuthenticationException || $throwable instanceof AccessDeniedException;
    }
}
