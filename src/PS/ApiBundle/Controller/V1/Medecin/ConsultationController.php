<?php

namespace PS\ApiBundle\Controller\V1\Medecin;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use PS\GestionBundle\Entity\Consultation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;

class ConsultationController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"consultation", "specialite"})
     * @QueryParam(name="page", requirements="\d+", default="", description="Index de début de la pagination")
     * @Rest\Get("medecins/{id}/consultations", condition="request.attributes.get('version') == 'v1'")
     */
    public function getConsultationsAction(Request $request, ParamFetcher $paramFetcher)
    {
        $em       = $this->getDoctrine()->getEntityManager();
        $id       = $request->get('id');
        $rep      = $em->getRepository(Consultation::class);
        $page     = intval($paramFetcher->get('page'));
        $result   = 10;
        $maxPages = ceil($rep->countByMedecin($id) / $result);

        if ($page <= 0 || $page > $maxPages) {
            $page = 1;
        }

        $limit  = $result;
        $offset = ($page - 1) * $result;

        $consultations = $rep
            ->findByMedecin(
                $id
                , $limit
                , $offset
            );

        return ['maxPages' => $maxPages, 'results' => $consultations];

    }
}
