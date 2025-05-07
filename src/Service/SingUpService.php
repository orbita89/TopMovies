<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\UserAlreadyException;
use App\Model\IdResponse;
use App\Model\SignUpRequest;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SingUpService
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $hasher,
    ) {
    }

    public function singUp(SignUpRequest $signUpRequest): IdResponse
    {

        $email = strtolower($signUpRequest->getEmail());

        if ($this->userRepository->existsByEmail($email)) {
            throw new UserAlreadyException();
        }

        $user = new User();

        $user->setFirstName($signUpRequest->getFirstName());
        $user->setLastName($signUpRequest->getLastName());
        $user->setEmail($signUpRequest->getEmail());
        $user->setPassword(
            $this->hasher->hashPassword(
                $user,
                $signUpRequest->getPassword()
            )
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return (new IdResponse($user->getId()));

    }
}

