<?php

namespace App\Service;

use App\Entity\Book;
use App\Exception\SlugAlreadyException;
use App\Model\Author\Author\Author\CreateBookRequest;
use App\Model\Author\BookListItem;
use App\Model\Author\BookListResponse;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class AuthorService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BookRepository $bookRepository,
        private SluggerInterface $slugger,
        private Security $security

    ) {
    }

    public function getBooks(): BookListResponse
    {
        $user = $this->security->getUser();

        return new BookListResponse(
            array_map([$this, 'map'], $this->bookRepository->findUserBooks($user)),
        );
    }

    public function createBook(CreateBookRequest $request): Book
    {
        $slug = $this->slugger->slug($request->getTitle());
        if ($this->bookRepository->existsBySlug($slug)) {
            throw new SlugAlreadyException();
        }

        $book = new Book();
        $book->setTitle($request->getTitle())
            ->setMeap(false)
            ->setSlug($slug)
            ->setUser($this->security->getUser());

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return $book;
    }


    public function deleteBook(int $id): void
    {
        $user = $this->security->getUser();
        $book = $this->bookRepository->getUserBookById($id, $user);

        $this->entityManager->remove($book);
        $this->entityManager->flush();
    }

    private function map(Book $book): BookListItem
    {
        return (new BookListItem())
            ->setTitle($book->getTitle())
            ->setSlug($book->getSlug())
            ->setImage($book->getImage())
            ->setId($book->getId());
    }

}
