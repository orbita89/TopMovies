<?php

namespace App\Service\Recommendation\Exception;

class RecommendationException extends \Exception
{
    public function __construct(string $message = 'Recommendation error', ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
