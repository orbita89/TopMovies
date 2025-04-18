<?php

namespace App\Tests\Service;

use App\Entity\BookCategory;
use App\Model\BookCategoryListItem;
use App\Model\BookCategoryListResponse;
use App\Repository\BookCategoryRepository;
use App\Service\BookCategoryService;
use PHPUnit\Framework\TestCase;

class BookCategoryServiceTest extends TestCase
{

    public function testGetBookCategories()
    {
        $repository = $this->createMock(BookCategoryRepository::class);

        $repository->expects($this->once())->method('findBy')->with([], ['title' => 'ASC'])->willReturn([
            (new BookCategory())->setId(7)->setTitle('Test')->setSlug('Test')
        ]);

        $service = new BookCategoryService($repository);

        $expected = new BookCategoryListResponse(items: [new BookCategoryListItem(id: '7', title: 'Test', slug: 'Test')]
        );

        $this->assertEquals($expected, $service->getBookCategories());
    }
}
