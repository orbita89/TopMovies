<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class BookToBookFormat
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $price;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $discountPercent = null;

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'bookToBookFormats')]
    #[ORM\JoinColumn(nullable: false)]
    private Book $book;

    #[ORM\ManyToOne(targetEntity: BookFormat::class, fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false)]
    private BookFormat $bookFormat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): BookToBookFormat
    {
        $this->id = $id;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): BookToBookFormat
    {
        $this->price = $price;
        return $this;
    }

    public function getDiscountPercent(): ?int
    {
        return $this->discountPercent;
    }

    public function setDiscountPercent(?int $discountPercent): BookToBookFormat
    {
        $this->discountPercent = $discountPercent;
        return $this;
    }

    public function getBook(): Book
    {
        return $this->book;
    }

    public function setBook(Book $book): BookToBookFormat
    {
        $this->book = $book;
        return $this;
    }

    public function getBookFormat(): BookFormat
    {
        return $this->bookFormat;
    }

    public function setBookFormat(BookFormat $bookFormat): BookToBookFormat
    {
        $this->bookFormat = $bookFormat;
        return $this;
    }
}
