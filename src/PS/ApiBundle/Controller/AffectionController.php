<?php

namespace PS\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PS\ParametreBundle\Entity\Affection;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle

class AffectionController extends Controller
{
   /**
     * @Rest\View()
     * @Rest\Get("/affections")
     */
   public function getAffectionsAction()
   {
        $data = $this->get('doctrine.orm.entity_manager')
                ->getRepository(Affection::class)
                ->findAll();
        /* @var $data Affection[] */
        return $data;
   }
}
