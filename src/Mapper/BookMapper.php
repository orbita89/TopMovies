<?php

namespace App\Mapper;

use App\Entity\Book;
use App\Model\RecommendedBook;

class BookMapper
{


    public static function mapRecommendedBook(Book $book): RecommendedBook
    {
        $description = $book->getDescription();
        $shortDescription = strlen($description) > 150 ? substr($description, 0, 150).'...' : $description;

        return (new RecommendedBook())
            ->setId($book->getId())
            ->setTitle($book->getTitle())
            ->setSlug($book->getSlug())
            ->setImage($book->getImage())
            ->setShortDescription($shortDescription);
    }
}
