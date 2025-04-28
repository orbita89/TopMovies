<?php

namespace App\Service;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\BookToBookFormat;
use App\Exception\BookCategoryNotFoundException;
use App\Model\BookCategory as BookCategoryModel;
use App\Model\BookDetails;
use App\Model\BookFormat;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Repository\ReviewRepository;

class BookService
{
    public function __construct(
        private BookRepository $bookRepository,
        private BookCategoryRepository $bookCategoryRepository,
        private ReviewRepository $reviewRepository
    ) {
    }

    public function getBookByCategory(int $categoryId): BookListResponse
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

    private function map(Book $book): BookListItem
    {
        return (new BookListItem())
            ->setId($book->getId())
            ->setTitle($book->getTitle())
            ->setSlug($book->getSlug())
            ->setAuthors($book->getAuthors())
            ->setImage($book->getImage())
            ->setMeap($book->isMeap())
            ->setPublicationDate($book->getCreatedAt()->getTimestamp());
    }

    public function getBookById(int $id): BookDetails
    {
        $book = $this->bookRepository->getById($id);

        $reviews = $this->reviewRepository->countByBookId($id);
        $ratingsSum = $this->reviewRepository->getBookTotalRatingSum($id);

        $formats = $book->getBookFormats()
            ->map(fn(BookToBookFormat $formatJoin) => (new BookFormat())
                ->setId($formatJoin->getBookFormat()->getId())
                ->setTitle($formatJoin->getBookFormat()->getTitle())
                ->setComment($formatJoin->getBookFormat()->getComment())
                ->setPrice($formatJoin->getPrice())
                ->setDiscountPercent($formatJoin->getDiscountPercent())
                ->setDescription($formatJoin->getBookFormat()->getDescription())
            );

        $categories = $book->getCategories()
            ->map(fn(BookCategory $category) => new BookCategoryModel(
                id: $category->getId(),
                title: $category->getTitle(),
                slug: $category->getSlug()
            ));

        return (new BookDetails())
            ->setId($book->getId())
            ->setTitle($book->getTitle())
            ->setSlug($book->getSlug())
            ->setAuthors($book->getAuthors())
            ->setImage($book->getImage())
            ->setMeap($book->isMeap())
            ->setPublicationDate($book->getCreatedAt()->getTimestamp())
            ->setRating($reviews == 0 ? 0 : $ratingsSum / $reviews)
            ->setRewiew($reviews)
            ->setBookFormats($formats->toArray())
            ->setCategories($categories->toArray());
    }
}
