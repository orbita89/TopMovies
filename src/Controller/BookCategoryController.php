<?php

namespace App\Controller;

use App\Service\BookCategoryService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookCategoryController extends AbstractController
{
    public function __construct(private BookCategoryService $bookCategoryService)
    {
    }

    #[OA\Get(
        path: '/your/path',
        description: 'Get list of categories',
        responses: [new OA\Response(response: 200, description: 'OK')]
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(ref: '#/components/schemas/BoolCategoryListResponse')
    )]
    #[Route('/api/v1/book/categories', name: 'api_book_categories', methods: ['GET'])]
    public function categories(): Response
    {
        return $this->json($this->bookCategoryService->getBookCategories());
    }
}
