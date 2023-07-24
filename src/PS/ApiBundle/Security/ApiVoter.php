<?php

namespace PS\ApiBundle\Security;

use PS\UtilisateurBundle\Entity\Utilisateur;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Entity\Medecin;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;


class ApiVoter extends Voter
{
    
}