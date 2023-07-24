<?php

namespace PS\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PS\ParametreBundle\Entity\Medicament;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle

class MedicamentController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/medicaments")
     */
   public function getMedicamentsAction()
   {
        $data = $this->get('doctrine.orm.entity_manager')
                ->getRepository(Medicament::class)
                ->findAll();
        /* @var $data Medicament[] */
        return $data;
   }
}
