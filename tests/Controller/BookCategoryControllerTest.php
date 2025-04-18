<?php

namespace App\Tests\Controller;

use App\Controller\BookCategoryController;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookCategoryControllerTest extends WebTestCase
{

    public function testCategories()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/v1/book/categories');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        $this->assertJsonStringEqualsJsonFile(
            __DIR__.'/response/BookCategoryControllerTest_testCategories.json',
            $client->getResponse()->getContent()
        );
    }
}
