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
}
