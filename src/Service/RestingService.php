<?php

namespace App\Service;

use App\Repository\ReviewRepository;

class RestingService
{
    public function __construct(private ReviewRepository $reviewRepository)
    {
    }

    public function calcReviewRatingForBook()
    {
    }

    public function getReviewRepository(): ReviewRepository
    {
        return $this->reviewRepository;
    }

    public function setReviewRepository(ReviewRepository $reviewRepository): RestingService
    {
        $this->reviewRepository = $reviewRepository;
        return $this;
    }
}
