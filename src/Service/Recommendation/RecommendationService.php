<?php

namespace App\Service\Recommendation;

use App\Service\Recommendation\Exception\RequestException;
use App\Service\Recommendation\Model\RecommendationListResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class RecommendationService
{

    public function __construct(
        private HttpClientInterface $recommendationClient,
        private SerializerInterface $serializer
    ) {
    }

    /**
     * @param  int  $bookId
     * @return RecommendationListResponse
     * @throws RequestException
     */
    public function getRecommendations(int $bookId): RecommendationListResponse
    {
        try {
            $response = $this->recommendationClient->request('GET', '/api/v1/book/'.$bookId.'/recommendation');

            $content = $response->getContent();

            return $this->serializer->deserialize(
                data: $response->getContent(),
                type: RecommendationListResponse::class,
                format: 'json'
            );
        } catch (Throwable $e) {
            if ($e instanceof TransportExceptionInterface && Response::HTTP_FORBIDDEN === $e->getCode()) {
                throw new AccessDeniedException($e);
            }
            throw new RequestException($e->getMessage(), $e);
        }
    }
}
