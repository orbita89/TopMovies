<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Review|null find(mixed $id, LockMode|int|null $lockMode = null, int|null $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array|null $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array|null $orderBy = null, int|null $limit = null, int|null $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function countByBookId(int $id): int
    {
        return $this->count(['book' => $id]);
    }

    public function getBookTotalRatingSum(int $bookId): ?int
    {
        $qb = $this->createQueryBuilder('r')
            ->select('SUM(r.rating)')
            ->where('r.book = :bookId')
            ->setParameter('bookId', $bookId);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function getPageByBookId(int $id, int $limit, int $offset)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT r
             FROM App\Entity\Review r
             WHERE r.book = :id
             ORDER BY r.createdAt DESC'
        )->setParameter('id', $id)->setFirstResult($offset)->setMaxResults($limit);

        return new Paginator($query, false);
    }
}
