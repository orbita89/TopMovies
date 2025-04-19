<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\BookCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 * @method BookCategory|null find(mixed $id, LockMode|int|null $lockMode = null, int|null $lockVersion = null)
 * @method BookCategory|null findOneBy(array $criteria, array|null $orderBy = null)
 * @method BookCategory[] findAll()
 * @method BookCategory[] findBy(array $criteria, array|null $orderBy = null, int|null $limit = null, int|null $offset = null)
 */
class BookCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookCategory::class);
    }


    public function findAllSortingByTitle(): array
    {
        return $this->findBy([], ['title' => 'ASC']);
    }
}
