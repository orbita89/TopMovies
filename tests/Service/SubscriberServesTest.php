<?php

namespace App\Tests\Service;

use App\Entity\Subscriber;
use App\Exception\SubscriberAlreadyException;
use App\Model\SubscriberRequest;
use App\Repository\SubscriberRepository;
use App\Service\SubscriberServes;
use App\Tests\AbstractTestCase;
use Doctrine\ORM\EntityManagerInterface;

class SubscriberServesTest extends AbstractTestCase
{
    private SubscriberRepository $subscriberRepository;
    private EntityManagerInterface $entityManager;
    private SubscriberServes $subscriberServes;

    protected function setUp(): void
    {
        $this->subscriberRepository = $this->createMock(SubscriberRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->subscriberServes = new SubscriberServes(
            $this->subscriberRepository,
            $this->entityManager
        );
    }

    public function testSubscriberAlreadyExists(): void
    {
        $request = $this->createMock(SubscriberRequest::class);
        $request->method('getEmail')->willReturn('test@example.com');

        $this->subscriberRepository
            ->method('existsByEmail')
            ->with('test@example.com')
            ->willReturn(true);

        $this->expectException(SubscriberAlreadyException::class);

        $this->subscriberServes->subscriber($request);
    }

    public function testSubscriberSuccessfullyCreated(): void
    {
        $request = $this->createMock(SubscriberRequest::class);
        $request->method('getEmail')->willReturn('new@example.com');

        $this->subscriberRepository
            ->method('existsByEmail')
            ->with('new@example.com')
            ->willReturn(false);

        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Subscriber::class));

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->subscriberServes->subscriber($request);
    }
}
