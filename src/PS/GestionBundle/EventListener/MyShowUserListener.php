<?php
namespace PS\GestionBundle\EventListener;

// ...

use Avanzu\AdminThemeBundle\Event\ShowUserEvent;
use PS\UtilisateurBundle\Entity\Utilisateur;
use PS\GestionBundle\Model\UserModel;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class MyShowUserListener {

    // ...
    /*protected $context;

    public function __construct(TokenStorage $context)
    {
        $this->context = $context;
    }*/

    public function onShowUser(ShowUserEvent $event) {

        $user = $this->getUser();
        $event->setUser($user);

    }

    protected function getUser() {
        // retrieve your concrete user model or entity
        /*if ($this->context->getToken()) {
            return $user = $this->context->getToken()->getUser();
        }*/
    }

}