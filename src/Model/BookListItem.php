<?php

namespace App\Model;

class BookListItem
{
    private int $id;
    private string $title;
    private string $slug;
    private ?string $image = null;
    private array $authors = [];
    private bool $meap = false;
    private int $publicationDate;

    public function setId(int $id): BookListItem
    {
        $this->id = $id;
        return $this;
    }

    public function setSlug(string $slug): BookListItem
    {
        $this->slug = $slug;
        return $this;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): BookListItem
    {
        $this->title = $title;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): BookListItem
    {
        $this->image = $image;
        return $this;
    }

    public function getAuthors(): array
    {
        return $this->authors;
    }

    public function setAuthors(array $authors): BookListItem
    {
        $this->authors = $authors;
        return $this;
    }

    public function getMeap(): bool
    {
        return $this->meap;
    }

    public function setMeap(bool $meap): BookListItem
    {
        $this->meap = $meap;
        return $this;
    }

    public function getPublicationDate(): int
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(int $publicationDate): BookListItem
    {
        $this->publicationDate = $publicationDate;
        return $this;
    }


}
