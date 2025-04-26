<?php

namespace App\Tests\Controller;

use App\Controller\SubscriberController;
use App\Exception\SubscriberAlreadyException;
use App\Model\SubscriberRequest;
use App\Service\SubscriberServes;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SubscriberControllerTest extends WebTestCase
{

    public function testSubscriberAction(): void
    {
        $client = static::createClient();

        $subscriberService = $this->createMock(SubscriberServes::class);
        $subscriberService
            ->expects($this->once())
            ->method('subscriber')
            ->with($this->isInstanceOf(SubscriberRequest::class));

        static::getContainer()->set(SubscriberServes::class, $subscriberService);

        $client->request(
            'POST',
            '/api/v1/subscriber',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'test@example.com',
                'agreed' => true
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }


    public function testSubscriberAlreadyExists(): void
    {
        $client = static::createClient();

        $subscriberService = $this->createMock(SubscriberServes::class);
        $subscriberService
            ->method('subscriber')
            ->willThrowException(new SubscriberAlreadyException());

        static::getContainer()->set(SubscriberServes::class, $subscriberService);

        $client->request(
            'POST',
            '/api/v1/subscriber',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'existing@example.com',
                'agreed' => true
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CONFLICT);
    }

    public function testSubscriberValidationError(): void
    {
        $client = static::createClient();

        // Можно реальный сервис оставить (так как ошибка валидации возникнет раньше)
        $client->request(
            'POST',
            '/api/v1/subscriber',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'not-an-email',
                'agreed' => true
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testSubscriberMissingFields(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/v1/subscriber',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}
