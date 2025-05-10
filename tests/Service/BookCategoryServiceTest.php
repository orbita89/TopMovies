<?php

namespace App\Tests\Service;

use App\Entity\BookCategory;
use App\Model\BookCategory as BookCategoryModel;
use App\Model\BookCategoryListResponse;
use App\Repository\BookCategoryRepository;
use App\Service\BookCategoryService;
use App\Tests\AbstractTestCase;

class BookCategoryServiceTest extends AbstractTestCase
{
    public function testGetBookCategories()
    {
        $category = (new BookCategory())->setTitle('Test')->setSlug('Test');

        $this->setEntityId($category, 7);

        $repository = $this->createMock(BookCategoryRepository::class);

        $repository->expects($this->once())
            ->method('findAllSortingByTitle')
            ->willReturn([$category]);

        $service = new BookCategoryService($repository);

        $expected = new BookCategoryListResponse(items: [new BookCategoryModel(id: '7', title: 'Test', slug: 'Test')]
        );

        $this->assertEquals($expected, $service->getBookCategories());
    }
}
