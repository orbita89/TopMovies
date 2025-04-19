<?php

namespace App\Tests\Service;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Exception\BookCategoryNotFoundException;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Service\BookService;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class BookServiceTest extends TestCase
{
    public function testGetBookByCategoryReturnsBooks()
    {
        $bookRepository = $this->createMock(BookRepository::class);
        $bookRepository->expects($this->once())
            ->method('findBookByCategory')
            ->with(130)
            ->willReturn([$this->getBookEntity()]);


        $category = $this->createMock(BookCategoryRepository::class);
        $bookCategory = (new BookCategory())->setId(130);

        $category = $this->createMock(BookCategoryRepository::class);
        $category->expects($this->once())
            ->method('find')
            ->with(130)
            ->willReturn($bookCategory);


        $service = new BookService($bookRepository, $category);

        $expected = new BookListResponse([$this->getSetCategories()]);

        $this->assertEquals($expected, $service->getBookByCategory(130));
    }

    public function testGetBookByIdReturnsNullIfNotFound()
    {
        $bookRepository = $this->createMock(BookRepository::class);
        $bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $bookCategoryRepository->expects($this->once())
            ->method('find')
            ->with(999)
            ->willThrowException(new BookCategoryNotFoundException());


        $this->expectException(BookCategoryNotFoundException::class);

        (new BookService($bookRepository, $bookCategoryRepository))->getBookByCategory(999);
    }

    public function getBookEntity(): Book
    {
        return (new Book())
            ->setId(130)
            ->setTitle('test')
            ->setSlug('test')
            ->setMeap(false)
            ->setAuthors(['test'])
            ->setImage('http://test.com')
            ->setCreatedAt(new \DateTime('2023-01-01'))
            ->setCategories(new ArrayCollection());
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
