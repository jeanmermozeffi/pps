<?php

namespace PS\ApiBundle\EventListener;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Doctrine\UserManager;
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
    public function __construct(AuthorizationCheckerInterface $auth, EntityManager $em, UserManager $user)
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
        $idPersonne = $user->getPersonne()->getId();

        
        
        $payload['id'] = $user->getId();
        $payload['personneid'] = $idPersonne;

        if ($idPersonne) {
            if ($this->auth->isGranted('ROLE_MEDECIN') && !$this->auth->isGranted('ROLE_ADMIN')) {
                $payload['medecinid'] = $this->em->getRepository(Medecin::class)->findIdByPersonne($idPersonne);
            } else {
                $payload['patientid'] =  $this->em->getRepository(Patient::class)->findIdByPersonne($idPersonne);
            }
        }

        //$payload['refresh_token'] = bin2hex(random_bytes(256));

        $event->setData($payload);
    }
}