<?php

namespace App\Listener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JwtCreatedListener
{
    public function __invoke(JWTCreatedEvent $event): void
    {
         $user = $event->getUser();
         $data = $event->getData();
         $data['id'] = $user->getUserIdentifier();

         $event->setData($data);
    }
}
