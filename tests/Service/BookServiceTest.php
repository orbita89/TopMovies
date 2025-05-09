<?php

namespace App\Tests\Service;

use App\Entity\Book;
use App\Exception\BookCategoryNotFoundException;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Service\BookService;
use App\Tests\AbstractTestCase;
use Doctrine\Common\Collections\ArrayCollection;

class BookServiceTest extends AbstractTestCase
{
    public function testGetBookByCategoryReturnsBooks()
    {
        $bookRepository = $this->createMock(BookRepository::class);
        $bookRepository->expects($this->once())
            ->method('findBookByCategory')
            ->with(130)
            ->willReturn([$this->getBookEntity()]);

        $category = $this->createMock(BookCategoryRepository::class);
        $category->expects($this->once())
            ->method('existById')
            ->with(130)
            ->willReturn(true);

        $service = new BookService($bookRepository, $category);

        $expected = new BookListResponse([$this->getSetCategories()]);

        $this->assertEquals($expected, $service->getBookByCategory(130));
    }

    public function testGetBookByIdReturnsNullIfNotFound()
    {
        $bookRepository = $this->createMock(BookRepository::class);
        $bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $bookCategoryRepository->expects($this->once())
            ->method('existById')
            ->with(999)
            ->willReturn(false);

        $this->expectException(BookCategoryNotFoundException::class);

        (new BookService($bookRepository, $bookCategoryRepository))->getBookByCategory(999);
    }

    public function getBookEntity(): Book
    {
        $book = (new Book())
            ->setTitle('test')
            ->setSlug('test')
            ->setMeap(false)
            ->setAuthors(['test'])
            ->setImage('http://test.com')
            ->setCreatedAt(new \DateTime('2023-01-01'))
            ->setCategories(new ArrayCollection());

        $this->setEntityId($book, 130);

        return $book;
    }

    public function getSetCategories(): BookListItem
    {
        return (new BookListItem())
            ->setId(130)
            ->setTitle('test')
            ->setSlug('test')
            ->setMeap(false)
            ->setAuthors(['test'])
            ->setImage('http://test.com')
            ->setPublicationDate(1672531200);
    }
}
