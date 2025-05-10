<?php

namespace App\Model\Author;

class UploadCoverResponse
{
    public function __construct(private string $url)
    {
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): UploadCoverResponse
    {
        $this->url = $url;

        return $this;
    }
}
