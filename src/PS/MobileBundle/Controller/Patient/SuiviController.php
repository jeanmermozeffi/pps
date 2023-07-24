<?php

namespace PS\MobileBundle\Controller\Patient;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Entity\Suivi;
use PS\MobileBundle\Controller\ApiTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SuiviController extends Controller
{
    use ApiTrait;

    /**
     * @Rest\View(serializerGroups={"suivi-patient", "specialite", "medecin", "personne", "hopital", "patient-affection"})
     * @QueryParam(name="page", requirements="\d+", default=1, description="Index de début de la pagination")
     * @QueryParam(name="limit", requirements="\d+", default=10, description="Nombre d'éléments")
     * @Rest\Get("/patients/{id}/suivis")
     */
    public function getSuivisAction(Request $request, ParamFetcherInterface $paramFetcher)
    {

        $id       = $request->get('id');
        $rep      = $this->getRepository(Suivi::class);
        $page     = intval($paramFetcher->get('page'));
        $limit    = intval($paramFetcher->get('limit'));
        $maxPages = ceil($rep->countByPatient($id) / $limit);

        if ($page <= 0 || $page > $maxPages) {
            $page = 1;
        }

        $offset = ($page - 1) * $limit;

        $suivis = $rep
            ->findAllByPatient(
                $id,
                null,
                $limit,
                $offset
            );

        return ['maxPages' => $maxPages, 'data' => $suivis];
    }

    /**
     * @Rest\View(serializerGroups={"suivi-patient", "specialite", "medecin", "personne", "hopital", "patient-affection"})
     * @Rest\Get("/patients/{id}/suivis/{suivi}")
     */
    public function getSuiviAction(Request $request)
    {
        $suivi = $this->getRepository(Suivi::class)->findOneBy(['patient' => $request->get('id'), 'id' => $request->get('suivi')]);

        if ($suivi) {
            return $suivi;
        }

        return $this->notFound('suivi avec ID ' . $request->get('suivi') . ' inexistant ou n\'est pas associé à votre compte');
    }
}
