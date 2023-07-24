<?php

namespace PS\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PS\ParametreBundle\Entity\Specialite;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle

class SpecialiteController extends Controller
{
   /**
     * @Rest\View(serializerGroups={"specialite"})
     * @Rest\Get("/specialites")
     */
   public function getSpecialitesAction()
   {
        $data = $this->get('doctrine.orm.entity_manager')
                ->getRepository(Specialite::class)
                ->findAll();
        /* @var $data Specialite[] */
        return $data;
   }
}
