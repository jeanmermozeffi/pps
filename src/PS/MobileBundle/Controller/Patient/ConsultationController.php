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



class ConsultationController extends Controller

{

    use ApiTrait;



    /**

     * @Rest\View(serializerGroups={"consultation", "specialite", "medecin", "personne", "hopital", "info-hopital", "pays", "info-patient", "photo", "consultation-analyse", "consultation-medicament"})

     * @QueryParam(name="page", requirements="\d+", default=1, description="Index de début de la pagination")

     * @QueryParam(name="limit", requirements="\d+", default=10, description="Nombre d'éléments")

     * @QueryParam(name="statut", requirements="\d+", description="Statut de la consultation")

     * @Rest\Get("/patients/{id}/consultations")

     */

    public function getConsultationsAction(Request $request, ParamFetcherInterface $paramFetcher)

    {



        $id       = $request->get('id');

        $rep      = $this->getRepository(Consultation::class);

        $page     = intval($paramFetcher->get('page'));

        $limit    = intval($paramFetcher->get('limit'));

        $statut = $paramFetcher->get('statut');



        $filters = array_filter(compact('statut'));

        $maxPages = ceil($rep->countByPatient($id, $filters) / $limit);



        if ($page <= 0 || $page > $maxPages) {

            $page = 1;

        }



        $offset = ($page - 1) * $limit;



        $consultations = $rep

            ->findByPatient(

                $id,

                null,

                $limit,

                $offset,

                $filters

            );



        return ['maxPages' => $maxPages, 'patient' => $this->getPatient($id), 'data' => $consultations];

    }



    /**

     * @Rest\View(serializerGroups={"consultation", "specialite", "medecin", "personne", "hopital", "info-hopital", "pays"})

     * @Rest\Get("/patients/{id}/consultations/{consultation}")

     */

    public function getConsultationAction(Request $request)
    {

        $consultation = $this->getRepository(Consultation::class)->findOneBy(['patient' => $request->get('id'), 'id' => $request->get('consultation')]);
        if ($consultation) {
            return $consultation;

        }

        return $this->notFound('consultation avec ID ' . $request->get('consultation') . ' inexistant ou n\'est pas associé à votre compte');

    }

}

