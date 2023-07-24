<?php

namespace PS\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PS\ParametreBundle\Entity\GroupeSanguin;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle

class GroupeSanguinController extends Controller
{
   /**
     * @Rest\View()
     * @Rest\Get("/groupe-sanguins")
     */
   public function getGroupeSanguinsAction()
   {
        $data = $this->getDoctrine()->getEntityManager()
                ->getRepository(GroupeSanguin::class)
                ->findAll();
        /* @var $data GroupeSanguin[] */
        return $data;
   }
}
