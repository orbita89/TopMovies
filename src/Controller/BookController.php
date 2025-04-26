<?php

namespace App\Controller;

use App\Service\BookService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{

    public function __construct(private BookService $bookService)
    {
    }

    #[OA\Get(
        path: '/api/v1/categories/{id}/books',
        summary: 'Получить список книг по категории',
        tags: ['Книги'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID категории',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 2)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Список книг успешно получен',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'items',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'title', type: 'string', example: 'Symfony in Action'),
                                    new OA\Property(property: 'slug', type: 'string', example: 'symfony-in-action'),
                                    new OA\Property(property: 'authors', type: 'array', items: new OA\Items(type: 'string')),
                                    new OA\Property(property: 'image', type: 'string', example: '/images/symfony.png'),
                                    new OA\Property(property: 'meap', type: 'boolean', example: false),
                                    new OA\Property(property: 'publicationDate', type: 'integer', example: 1713768412)
                                ],
                                type: 'object'
                            )
                        )
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Категория не найдена',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Категория не найдена')
                    ],
                    type: 'object'
                )
            )
        ]
    )]
    #[Route(path: '/api/v1/categories/{id}/books', name: 'api_book_categories_books', methods: ['GET'])]
    public function booksByCategory(int $id): Response
    {
        return $this->json($this->bookService->getBookByCategory($id));
    }
}
