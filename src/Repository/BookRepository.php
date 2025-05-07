<?php

namespace App\Repository;

use App\Entity\Book;
use App\Exception\BookNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\AbstractUnicodeString;

/**
 * @extends ServiceEntityRepository<Book>
 * @method Book|null find(mixed $id, LockMode|int|null $lockMode = null, int|null $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array|null $orderBy = null)
 * @method Book[] findAll()
 * @method Book[] findBy(array $criteria, array|null $orderBy = null, int|null $limit = null, int|null $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }


    public function findBookByCategory(int $id): array
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT b
         FROM App\Entity\Book b
         WHERE :category MEMBER OF b.categories'
        )->setParameter('category', $id);

        return $query->getResult();
    }

    public function getById(int $id): Book
    {
        $book = $this->find($id);
        if (null === $book) {
            throw new BookNotFoundException();
        }
        return $book;
    }

    /**
     * @return Book[]
     */
    public function findBooksByIds(array $ids): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Book[]
     */
    public function findUserBooks(UserInterface $user): array
    {
        return $this->findBy(['user' => $user]);
    }

    public function getUserBookById(int $id, UserInterface $user): Book
    {

        $book = $this->findOneBy(['id' => $id, 'user' => $user]);
        if (null === $book) {
            throw new BookNotFoundException();
        }

        return $book;
    }

    public function existsBySlug(AbstractUnicodeString $slug): bool
    {
        return null !== $this->findOneBy(['slug' => $slug]);
    }
}
