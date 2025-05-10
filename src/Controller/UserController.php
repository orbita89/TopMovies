<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class UserController extends AbstractController
{
    #[Route(path: '/api/v1/user/me', methods: ['GET']), \OpenApi\Attributes\Operation(
        summary: 'Получить информацию о текущем пользователе',
        description: 'Возвращает данные текущего аутентифицированного пользователя.',
        tags: ['User'],
        responses: [
            new \OpenApi\Attributes\Response(
                response: 200,
                description: 'Успешный ответ',
                content: new \OpenApi\Attributes\JsonContent(
                    properties: [
                        new \OpenApi\Attributes\Property(property: 'id', type: 'integer', example: 1),
                        new \OpenApi\Attributes\Property(property: 'username', type: 'string', example: 'example_user'),
                        new \OpenApi\Attributes\Property(property: 'roles', type: 'array', items: new \OpenApi\Attributes\Items(type: 'string'), example: ['ROLE_USER']),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function me(#[CurrentUser] UserInterface $user): Response
    {
        return $this->json($user);
    }
}
