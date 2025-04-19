<?php

namespace App\Controller;

use App\Exception\BookCategoryNotFoundException;
use App\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{

    public function __construct(private BookService $bookService)
    {
    }

    #[Route(path: '/api/v1/categories/{id}/books', name: 'api_book_categories_books', methods: ['GET'])]
    public function booksByCategory(int $id): Response
    {
        try {
            return $this->json($this->bookService->getBookByCategory($id));
        } catch (BookCategoryNotFoundException $e) {
            throw new HttpException($e->getCode(), $e->getMessage());
        }
    }

}
