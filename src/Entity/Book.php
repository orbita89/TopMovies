<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $slug;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: 'simple_array')]
    private array $authors;

    #[ORM\Column(type: 'string', length: 13, unique: true)]
    private ?string $isbn = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $description;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $meap;

    /**
     * @var Collection<BookCategory>
     */
    #[ORM\ManyToMany(targetEntity: BookCategory::class)]
    #[ORM\JoinTable(name: 'book_to_book_category')]
    private Collection $categories;

    /**
     * @var Collection<BookToBookFormat>
     */
    #[ORM\OneToMany(targetEntity: BookToBookFormat::class, mappedBy: 'book')]
    private Collection $bookFormats;

    /**
     * @var Collection<Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'book')]
    private Collection $reviews;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->bookFormats = new ArrayCollection();
    }

    public function getBookFormats(): Collection
    {
        return $this->bookFormats;
    }

    public function setBookFormats(Collection $bookFormats): Book
    {
        $this->bookFormats = $bookFormats;
        return $this;
    }


    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): Book
    {
        $this->slug = $slug;
        return $this;
    }

    public function getAuthors(): array
    {
        return $this->authors;
    }

    public function setAuthors(array $authors): Book
    {
        $this->authors = $authors;
        return $this;
    }

    public function isMeap(): bool
    {
        return $this->meap;
    }

    public function setMeap(bool $meap): Book
    {
        $this->meap = $meap;
        return $this;
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function setCategories(Collection $categories): Book
    {
        $this->categories = $categories;
        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(?string $isbn): Book
    {
        $this->isbn = $isbn;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Book
    {
        $this->description = $description;
        return $this;
    }

    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function setReviews(Collection $reviews): Book
    {
        $this->reviews = $reviews;
        return $this;
    }
}
