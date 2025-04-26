<?php

declare(strict_types=1);

namespace App\Controller;

use App\Attribute\MyRequestBody;
use App\Model\SubscriberRequest;
use App\Service\SubscriberServes;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class SubscriberController extends AbstractController
{

    public function __construct(private SubscriberServes $subscriberService)
    {
    }


    #[OA\Post(
        path: '/api/v1/subscriber',
        description: 'Creates a new subscriber',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email'],
                properties: [
                    new OA\Property(
                        property: 'email',
                        type: 'string',
                        format: 'email',
                        example: 'test@example.com'
                    ),
                    new OA\Property(
                        property: 'agreed',
                        type: 'bool',
                        example: true
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful response',
                content: new OA\JsonContent(type: 'string')
            )
        ]
    )]
    #[Route('/api/v1/subscriber', name: 'api_subscriber', methods: ['POST'])]
    public function action(#[MyRequestBody] SubscriberRequest $subscriberRequest): Response
    {
        $this->subscriberService->subscriber($subscriberRequest);
        return $this->json(null);
    }
}
