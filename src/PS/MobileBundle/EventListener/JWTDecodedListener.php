<?php

namespace PS\MobileBundle\EventListener;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


class JWTDecodedListener
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

     /**
     * @var AuthorizationCheckerInterface
     */
    private $auth;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $auth)
    {
        $this->tokenStorage = $tokenStorage;
        $this->auth = $auth;
    }

    /**
     * @param JWTDecodedEvent $event
     *
     * @return void
     */
    public function onJWTDecoded(JWTDecodedEvent $event)
    {
       // dump($event);
    }
}