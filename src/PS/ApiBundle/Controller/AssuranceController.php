<?php

namespace PS\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PS\ParametreBundle\Entity\Assurance;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle


class AssuranceController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/assurances")
     */
   public function getAssurancesAction()
   {
        $data = $this->get('doctrine.orm.entity_manager')
                ->getRepository(Assurance::class)
                ->findAll();
        /* @var $data Assurance[] */
        return $data;
   }
}
