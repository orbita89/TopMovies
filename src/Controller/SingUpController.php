<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\SignUpRequest;
use App\Service\SingUpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use App\Attribute\MyRequestBody;

#[OA\Tag(name: 'Auth')]
class SingUpController extends AbstractController
{
    public function __construct(private SingUpService $singUpService)
    {
    }

    #[OA\Post(
        path: '/api/v1/auth/signUp',
        summary: 'Регистрация пользователя',
        requestBody: new OA\RequestBody(
            required: true,
        ),
        responses: [
            new OA\Response(response: 200, description: 'Успешная регистрация'),
            new OA\Response(response: 400, description: 'Ошибка валидации'),
            new OA\Response(response: 409, description: 'Такой email существует'),
        ]
    )]
    #[Route('/api/v1/auth/signUp', name: 'app_sing_up', methods: ['POST'])]
    public function singUp(#[MyRequestBody] SignUpRequest $signUpRequest): Response
    {
        return $this->singUpService->singUp($signUpRequest);
    }
}
