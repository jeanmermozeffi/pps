<?php

namespace PS\ApiBundle\Controller\V1\Patient;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\Version;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Entity\RendezVous;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Version("v1")
 */
class RendezVousController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"rdv"})
     * @Rest\Get("patients/{id}/rendez-vous", condition="request.attributes.get('version') == 'v1'")
     * @QueryParam(name="page", requirements="\d+", default="", description="Index de début de la pagination")
     * @QueryParam(name="start")
     * @QueryParam(name="end")
     */
    public function getAllRendezVousAction(Request $request, ParamFetcher $paramFetcher)
    {
        $em    = $this->getDoctrine()->getEntityManager();
        $start = $paramFetcher->get('start');
        $end   = $paramFetcher->get('end');
        $id    = $request->get('id');

        $rep      = $em->getRepository(RendezVous::class);
        $page     = intval($paramFetcher->get('page'));
        $result   = 10; /*intval($paramFetcher->get('result'));*/
        $maxPages = ceil($rep->countByPatient($id) / $result);

        if ($page <= 0 || $page > $maxPages) {
            $page = 1;
        }

        $limit  = $result;
        $offset = ($page - 1) * $result;

        $rendezVous = $em->getRepository(RendezVous::class)->findAllRendezVous(
            $request->get('id')
            , $start
            , $end
            , $limit
            , $offset
        );

        return ['maxPages' => $maxPages, 'results' => $rendezVous];

    }
}
