<?php

namespace App\Service;

use App\Entity\Subscriber;
use App\Exception\SubscriberAlreadyException;
use App\Model\SubscriberRequest;
use App\Repository\SubscriberRepository;
use Doctrine\ORM\EntityManagerInterface;

class SubscriberServes
{
    public function __construct(
        private SubscriberRepository $subscriberRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function subscriber(SubscriberRequest $request): void
    {
        if ($this->subscriberRepository->existsByEmail($request->getEmail())) {
            throw new SubscriberAlreadyException();
        }

        $subscriber = new Subscriber();

        $subscriber->setEmail($request->getEmail());
        $this->entityManager->persist($subscriber);
        $this->entityManager->flush();
    }
}
