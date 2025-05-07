<?php

declare(strict_types=1);

namespace App\Controller;

use App\Attribute\MyRequestBody;
use App\Model\Author\Author\Author\CreateBookRequest;
use App\Service\AuthorService;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes\RequestBody;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class AuthorController extends AbstractController
{
    public function __construct(private AuthorService $authorService)
    {
    }

    #[Route('/api/v1/author/books', name: 'author_index', methods: ['GET'])]
    #[OA\Get(
        path: '/api/v1/author/books',
        summary: 'Получить список книг',
        tags: ['Author'],
    )]
    public function books(): Response
    {
        return $this->json($this->authorService->getBooks());
    }

    #[Route('/api/v1/author/book', name: 'author_create', methods: ['POST'])]
    #[OA\Post(
        path: '/api/v1/author/book',
        summary: 'Создать книгу',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: new Model(type: CreateBookRequest::class))
        ),
        tags: ['Author'],
        responses: [
            new OA\Response(response: 200, description: 'Книга создана')
        ]
    )]
    public function createBook(#[MyRequestBody] CreateBookRequest $request): Response
    {
        $this->authorService->createBook($request);
        return $this->json(null);
    }

    #[Route('/api/v1/author/book/{id}', name: 'author_delete', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/api/v1/author/book/{id}',
        summary: 'Удалить книгу по ID',
        tags: ['Author'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Книга удалена')
        ]
    )]
    public function deleteBook(int $id): Response
    {
        $this->authorService->deleteBook($id);
        return $this->json(null);
    }
}
