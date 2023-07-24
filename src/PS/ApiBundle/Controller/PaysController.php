<?php

namespace PS\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PS\ParametreBundle\Entity\Pays;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle

class PaysController extends Controller
{
   /**
     * @Rest\View(serializerGroups={"pays"})
     * @Rest\Get("/pays")
     */
   public function getPaysAction()
   {
        $data = $this->getDoctrine()->getEntityManager()
                ->getRepository(Pays::class)
                ->findAll();
        /* @var $data Pays[] */
        return $data;
   }
}
