<?php

namespace PS\MobileBundle\EventListener;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTAuthenticatedEvent;



class JWTAuthenticatedListener
{

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTAuthenticated(JWTAuthenticatedEvent $event)
    {   
        $token = $event->getToken();
        $payload = $event->getPayload();
    }
}