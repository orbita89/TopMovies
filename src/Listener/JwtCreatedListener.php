<?php

namespace App\Listener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JwtCreatedListener
{
    public function __invoke(JWTCreatedEvent $event): void
    {
        /** @var User $user */
         $user = $event->getUser();
         $data = $event->getData();
         $data['id'] = $user->getId();

         $event->setData($data);
    }
}
