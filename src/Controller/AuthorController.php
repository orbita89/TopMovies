<?php

declare(strict_types=1);

namespace App\Controller;

use App\Attribute\MyRequestBody;
use App\Attribute\MyRequestFile;
use App\Model\Author\CreateBookRequest;
use App\Model\Author\PublishBookRequest;
use App\Service\AuthorService;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class AuthorController extends AbstractController
{
    public function __construct(private AuthorService $authorService)
    {
    }

    #[Route('/api/v1/author/book/{id}/publish', methods: ['POST'])]
    #[OA\Post(
        path: '/api/v1/author/book/{id}/publish',
        summary: 'Опубликовать книгу',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: new Model(type: PublishBookRequest::class))
        ),
        tags: ['Author'],
        responses: [
            new OA\Response(response: 200, description: 'Опубликована книга'),
        ]
    )]
    public function publish(
        int $id,
        #[MyRequestBody] PublishBookRequest $request,
    ): Response {
        $as = $request->getDate();
        $this->authorService->publish($id, $request);

        return $this->json(null);
    }

    #[Route('/api/v1/author/book/{id}/unpublish', name: 'author_unpublish', methods: ['POST'])]
    #[OA\Post(
        path: '/api/v1/author/book/{id}/unpublish',
        summary: 'Опубликовать книгу',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: new Model(type: PublishBookRequest::class))
        ),
        tags: ['Author'],
        responses: [
            new OA\Response(response: 200, description: 'Не опубликована книга'),
        ]
    )]
    public function unPublish(int $id): Response
    {
        $this->authorService->unPublish($id);

        return $this->json(null);
    }

    #[Route('/api/v1/author/book/{id}/uploadCover', name: 'author_upload_cover', methods: ['POST'])]
    public function uploadCover(
        int $id,
        #[MyRequestFile(field: 'cover', constraints: [
            new NotNull(),
            new Image(maxSize: '1M', mimeTypes: [
                'image/jpeg',
                'image/png',
                'image/jpg',
            ]),
        ])] UploadedFile $file,
    ): Response {
        return $this->json($this->authorService->uploadCover($id, $file));
    }

    #[Route('/api/v1/author/book/{id}/deleteCover', name: 'author_delete_cover', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/api/v1/author/book/{id}/deleteCover',
        summary: 'Удалить обложку книги',
        tags: ['Author'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Обложка удалена'),
        ]
    )]
    public function deleteCover(int $id): Response
    {
        $this->authorService->deleteCover($id);

        return $this->json(null);
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
            new OA\Response(response: 200, description: 'Книга создана'),
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
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Книга удалена'),
        ]
    )]
    public function deleteBook(int $id): Response
    {
        $this->authorService->deleteBook($id);

        return $this->json(null);
    }
}
