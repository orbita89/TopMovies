<?php

declare(strict_types=1);

namespace App\Controller;


use App\Service\ReviewService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class ReviewController extends AbstractController
{

    public function __construct(private ReviewService $reviewService)
    {
    }

    #[OA\Get(
        path: '/api/v1/book/{id}/reviews',
        operationId: 'getBookReviews',
        description: 'Возвращает страницу с отзывами для книги по её ID.',
        summary: 'Получить отзывы по книге',
        tags: ['Отзывы'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID книги',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'page',
                description: 'Номер страницы',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешный ответ',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'rating', type: 'integer', example: 5),
                        new OA\Property(property: 'total', type: 'integer', example: 12),
                        new OA\Property(property: 'page', type: 'integer', example: 1),
                        new OA\Property(property: 'perPage', type: 'integer', example: 5),
                        new OA\Property(property: 'pages', type: 'integer', example: 3),
                        new OA\Property(
                            property: 'items',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'content', type: 'string', example: 'Очень хорошая книга!'),
                                    new OA\Property(property: 'author', type: 'string', example: 'Иван Иванов'),
                                    new OA\Property(property: 'rating', type: 'integer', example: 5),
                                    new OA\Property(property: 'createdAt', type: 'integer', example: 1714307412),
                                ],
                                type: 'object'
                            )
                        )
                    ],
                    type: 'object'
                )
            )
        ]
    )]
    #[Route(path: '/api/v1/book/{id}/reviews', name: 'api_book_reviews', methods: ['GET'])]
    public function getReviews(int $id, Request $request): Response
    {
        return $this->json([
            $this->reviewService->getReviewsPageByBookId(
                $id,
                $request->query->getInt('page', 1)
            )
        ]);
    }
}
