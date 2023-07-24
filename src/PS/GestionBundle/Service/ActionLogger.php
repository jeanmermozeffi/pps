<?php

namespace PS\GestionBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use PS\GestionBundle\Entity\Historique;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ActionLogger
{
    /**
     * @var mixed
     */
    private $token;

    /**
     * @var mixed
     */
    private $em;

    /**
     * @var mixed
     */
    private $session;

    /**
     * @param TokenStorage $token
     */
    public function __construct(TokenStorage $token, EntityManagerInterface $em, SessionInterface $session)
    {
        $this->token   = $token;
        $this->session = $session;
        $this->em      = $em;
    }

    /**
     * @param $action
     * @param $entity
     * @param $entityUser
     */
    public function add($action, $entity = null, $andFlush = false, $alias = null, $identifiant = null)
    {
        $currentUser = $this->token->getToken()->getUser();

        $historique  = new Historique();
        $historique->setLibelle($action);
        $historique->setUtilisateur(!is_string($currentUser) ? $currentUser : null);
        $historique->setAlias($alias);
        $historique->setSession($this->session->getId());
        $historique->setIdentifiant($identifiant);

        $historique->setPersonne($this->getPersonne($entity));

        $this->em->persist($historique);
        if ($andFlush) {
            $this->em->flush();
        }

    }

    /**
     * @param $entity
     * @return mixed
     */
    public function getPersonne($entity)
    {

        if (method_exists($entity, 'getPersonne')) {
            return $entity->getPersonne();
        }

        if (method_exists($entity, 'getPatient')) {
            return $entity->getPatient()->getPersonne();
        }
    }

}
