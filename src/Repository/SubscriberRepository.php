<?php

namespace App\Repository;

use App\Entity\Subscriber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
class SubscriberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscriber::class);
    }


    public function existsByEmail(string $email): bool
    {
        return $this->createQueryBuilder('s')
                ->select('1')
                ->where('s.email = :email')
                ->setParameter('email', $email)
                ->getQuery()
                ->getOneOrNullResult() !== null;
    }
}
