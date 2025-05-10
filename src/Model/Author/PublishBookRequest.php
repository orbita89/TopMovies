<?php

namespace App\Model\Author;

use Symfony\Component\Validator\Constraints\NotBlank;

class PublishBookRequest
{
    #[NotBlank]
    private \DateTimeImmutable $date;

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): PublishBookRequest
    {
        $this->date = $date;

        return $this;
    }
}
