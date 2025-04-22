<?php

namespace App\Service;

use App\Entity\Book;
use App\Exception\BookCategoryNotFoundException;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;

class BookService
{
    public function __construct(
        private BookRepository $bookRepository,
        private BookCategoryRepository $bookCategoryRepository
    ) {
    }

    public function getBookByCategory($categoryId): BookListResponse
    {
        if (!$this->bookCategoryRepository->existById($categoryId)) {
            throw new BookCategoryNotFoundException();
        }

        return new BookListResponse(
            array_map(
                [$this, 'map'],
                $this->bookRepository->findBookByCategory($categoryId)
            )
        );
    }

    private function map(Book $book)
    {
        return ((new BookListItem())
            ->setId($book->getId())
            ->setTitle($book->getTitle())
            ->setSlug($book->getSlug())
            ->setAuthors($book->getAuthors())
            ->setImage($book->getImage())
            ->setMeap($book->isMeap())
            ->setPublicationDate($book->getCreatedAt()->getTimestamp()));
    }

}
