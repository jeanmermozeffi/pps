<?php

namespace PS\MobileBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use PS\ApiBundle\Form\IdentificationType;
use PS\ApiBundle\Form\ProfilType;
use PS\GestionBundle\Entity\Patient;
use PS\ParametreBundle\Entity\Pass;
use PS\GestionBundle\Entity\Abonnement;
use PS\ParametreBundle\Entity\Pack;
use PS\UtilisateurBundle\Entity\Personne;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse; // Utilisation de la vue de FOSRestBundle
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{

    /**
     * @Rest\View()
     * @Rest\Post("/login")
     */
    public function postLoginAction(Request $request)
    {
    }
}