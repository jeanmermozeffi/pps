<?php

namespace PS\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PS\ParametreBundle\Entity\Ville;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle

class VilleController extends Controller
{
   /**
     * @Rest\View(serializerGroups={"Default", "ville"})
     * @Rest\Get("/villes")
     */
   public function getVillesAction()
   {
        $data = $this->get('doctrine.orm.entity_manager')
                ->getRepository(Ville::class)
                ->findAll();
        /* @var $data Ville[] */
        return $data;
   }

}
