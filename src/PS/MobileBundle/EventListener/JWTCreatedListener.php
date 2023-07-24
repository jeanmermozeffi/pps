<?php

namespace PS\MobileBundle\EventListener;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Entity\Medecin;


class JWTCreatedListener
{
    
     /**
     * @var AuthorizationCheckerInterface
     */
    private $auth;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var FOS\UserBundle\Doctrine\UserManager;
     */
    private $user;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(AuthorizationCheckerInterface $auth, EntityManagerInterface $em, UserManagerInterface $user)
    {
        $this->user = $user;
        $this->auth = $auth;
        $this->em = $em;
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {   
        
        $payload       = $event->getData();
        
        $username = $payload['username'];
        $user = $this->user->findUserBy(['username' => $username]);


        //dump($user);exit;
        

        
        
        $payload['id'] = $user->getId();
        $expiration = new \DateTime('+12 month');
        $expiration->setTime(2, 0, 0);

        $payload        = $event->getData();
        $payload['exp'] = $expiration->getTimestamp();

        $event->setData($payload);
        /*$payload['email'] = $user->getEmail();
        $payload['username'] = $user->getUsername();*/
        //$payload['encoder'] = $user->getEncoder();
        $payload['id_personne'] = $user->getPersonne()->getId();
        if ($this->auth->isGranted('ROLE_CUSTOMER')) {
            $payload['id_patient'] = $user->getPatient() ? $user->getPatient()->getId(): null;
            $payload['id_medecin'] = null;
        } else {
            $payload['id_medecin'] = $user->getMedecin() ? $user->getMedecin()->getId() : null;
            $payload['id_patient'] = null;
        }
        
        

        //$payload['refresh_token'] = bin2hex(random_bytes(256));

        $event->setData($payload);
    }
}