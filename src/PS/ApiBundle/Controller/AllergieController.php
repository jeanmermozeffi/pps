<?php

namespace PS\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PS\ParametreBundle\Entity\Allergie;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle

class AllergieController extends Controller
{
   /**
     * @Rest\View()
     * @Rest\Get("/allergies")
     */
   public function getAllergiesAction()
   {
        $data = $this->get('doctrine.orm.entity_manager')
                ->getRepository(Allergie::class)
                ->findAll();
        /* @var $data Allergie[] */
        return $data;
   }
}
