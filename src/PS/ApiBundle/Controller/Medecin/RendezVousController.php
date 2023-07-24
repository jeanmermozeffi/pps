<?php

namespace PS\ApiBundle\Controller\Medecin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PS\GestionBundle\Entity\RendezVous;
use FOS\RestBundle\Controller\Annotations as Rest;
use PS\GestionBundle\Entity\Patient;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher; 


class RendezVousController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("medecins/{id}/rendez-vous")
     * @QueryParam(name="offset", requirements="\d+", default="", description="Index de début de la pagination")
     * @QueryParam(name="limit", requirements="\d+", default="10", description="Index de fin de la pagination")
     * @QueryParam(name="start")
     * @QueryParam(name="end")
     */
    public function getMedecinRendezVousAction(Request $request, ParamFetcher $paramFetcher)
    {
        $start = $paramFetcher->get('start');
        $end = $paramFetcher->get('end');
        $limit = $paramFetcher->get('limit');
        $offset = $paramFetcher->get('offset');
        
        $em = $this->getDoctrine()->getEntityManager();
        return $em->getRepository(RendezVous::class)
                ->findByMedecin(
                    $request->get('id')
                    , $start
                    , $end
                    , false
                    , $limit
                    , $offset
                );
    }
}


