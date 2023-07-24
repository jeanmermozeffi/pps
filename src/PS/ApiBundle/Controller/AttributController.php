<?php

namespace PS\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PS\ParametreBundle\Entity\Attribut;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle

class AttributController extends Controller
{
   /**
     * @Rest\View(serializerGroups={"list-attribut", "attribut"})
     * @Rest\Get("/attributs")
     */
   public function getAttributsAction()
   {
        $data = $this->getDoctrine()->getEntityManager()
                ->getRepository(Attribut::class)
                ->findAll();
        /* @var $data Attribut[] */
        return $data;
   }
}
