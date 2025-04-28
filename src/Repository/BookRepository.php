<?php

namespace App\Repository;

use App\Entity\Book;
use App\Exception\BookNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

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
}
