<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\PayloadAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTUserProvider implements PayloadAwareUserProviderInterface
{

    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function loadUserByIdentifierAndPayload(string $identifier, array $payload): UserInterface
    {
        return $this->getUser('id', $payload['id'] ?? $identifier);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new \InvalidArgumentException("Unsupported user class.");
        }

        return $this->userRepository->find($user->getId());
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->getUser('email', $identifier);
    }

    private function getUser($key, $value): UserInterface
    {
        $user = $this->userRepository->findOneBy([$key => $value]);
        if (null === $user) {
            $e = new UserNotFoundException("User with $key '$value' not found.");
            $e->setUserIdentifier($value);

            throw $e;
        }

        return $user;
    }
}
