<?php

namespace App\Service;

use App\Entity\Review;
use App\Model\Review AS ReviewModel;
use App\Model\ReviewPage;
use App\Repository\ReviewRepository;

class ReviewService
{

    private const LIMIT = 5;

    public function __construct(private ReviewRepository $reviewRepository)
    {
    }

    public function getReviewsPageByBookId(int $id, int $page): ReviewPage
    {
        $offset = ($page - 1) * self::LIMIT;
        $paginator = $this->reviewRepository->getPageByBookId($id, self::LIMIT, $offset);

        $total = count($paginator);

        $rating = 0;

        if ($total > 0) {
            $rating = $this->reviewRepository->getBookTotalRatingSum($id) / $total;
        }

        return new ReviewPage()
            ->setRating($rating)
            ->setTotal($total)
            ->setPage($page)
            ->setPerPage(self::LIMIT)
            ->setPages((int)ceil($total / self::LIMIT))
            ->setItems(array_map([$this, 'map'], $paginator->getIterator()->getArrayCopy()));
    }


    public function map(Review $review): ReviewModel
    {
        return new ReviewModel()
            ->setId($review->getId())
            ->setContent($review->getContent())
            ->setAuthor($review->getAuthor())
            ->setRating($review->getRating())
            ->setCreatedAt($review->getCreatedAt()->getTimestamp());
    }
}
