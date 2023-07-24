<?php

namespace PS\MobileBundle\Controller\Patient;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use PS\GestionBundle\Entity\Consultation;
use PS\GestionBundle\Entity\Patient;
use PS\MobileBundle\Controller\ApiTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class OrdonnanceController extends Controller
{
    use ApiTrait;

    /**
     * @Rest\View(serializerGroups={"consultation", "specialite", "medecin", "personne", "hopital", "info-hopital", "pays", "consultation-medicament"})
     * @QueryParam(name="page", requirements="\d+", default=1, description="Index de début de la pagination")
     * @QueryParam(name="limit", requirements="\d+", default=10, description="Nombre d'éléments")
     * @Rest\Get("/patients/{id}/consultations/{consultation}/medicaments")
     */
    public function getMedicamentsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $consultation = $this->getRepository(Consultation::class)->findOneBy(['patient' => $request->get('id'), 'id' => $request->get('consultation')]);
        if ($consultation) {
            /**@var \Doctrine\Common\Collections\ArrayCollection */
            $medicaments = $consultation->getMedicaments();

            $id       = $request->get('id');

            $page     = intval($paramFetcher->get('page'));
            $limit    = intval($paramFetcher->get('limit'));

            $maxPages = ceil(count($medicaments) / $limit);

            if ($page <= 0 || $page > $maxPages) {
                $page = 1;
            }

            $offset = ($page - 1) * $limit;





            //$medicaments = array_slice($medicaments->toArray(), $offset, $limit);

            return ['maxPages' => $maxPages, 'data' => $medicaments->slice($offset, $limit)];
        }

        return $this->notFound('consultation avec ID ' . $request->get('consultation') . ' inexistant ou n\'est pas associé à votre compte');
    }

}
