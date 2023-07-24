<?php

namespace PS\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PS\ParametreBundle\Entity\Hopital;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle

class HopitalController extends Controller
{
   /**
     * @Rest\View()
     * @Rest\Get("/hopitaux")
     */
   public function getHopitauxAction()
   {
        $data = $this->get('doctrine.orm.entity_manager')
                ->getRepository(Hopital::class)
                ->findAll();
        /* @var $data Hopital[] */
        return $data;
   }
}
