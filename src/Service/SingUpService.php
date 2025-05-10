<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\UserAlreadyException;
use App\Model\SignUpRequest;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SingUpService
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $hasher,
        private AuthenticationSuccessHandler $successHandler,
    ) {
    }

    public function singUp(SignUpRequest $signUpRequest): Response
    {
        $email = strtolower($signUpRequest->getEmail());

        if ($this->userRepository->existsByEmail($email)) {
            throw new UserAlreadyException();
        }

        $user = new User();

        $user->setFirstName($signUpRequest->getFirstName());
        $user->setLastName($signUpRequest->getLastName());
        $user->setEmail($signUpRequest->getEmail());
        $user->setRoles(['ROLE_USER']);
        $user->setPassword(
            $this->hasher->hashPassword(
                $user,
                $signUpRequest->getPassword()
            )
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->successHandler->handleAuthenticationSuccess($user);
    }
}
