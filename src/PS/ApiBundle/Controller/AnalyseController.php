<?php

namespace PS\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PS\ParametreBundle\Entity\Analyse;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle

class AnalyseController extends Controller
{
   /**
     * @Rest\View()
     * @Rest\Get("/analyses")
     */
   public function getAnalysesAction()
   {
        $data = $this->get('doctrine.orm.entity_manager')
                ->getRepository(Analyse::class)
                ->findAll();
        /* @var $data Analyse[] */
        return $data;
   }
}
