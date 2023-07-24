<?php

namespace PS\ApiBundle\Controller\V1\Medecin;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\Version;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use PS\ApiBundle\Form\RendezVousType;
use PS\GestionBundle\Entity\Medecin;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Entity\RendezVous;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Version("v1")
 */
class RendezVousController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("medecins/{id}/rendez-vous", condition="request.attributes.get('version') == 'v1'")
     * @QueryParam(name="page", requirements="\d+", default="", description="Numéro de la page")
     * @QueryParam(name="start")
     * @QueryParam(name="end")
     */
    public function getMedecinRendezVousAction(Request $request, ParamFetcher $paramFetcher)
    {

        $em = $this->getDoctrine()->getEntityManager();

        $start = $paramFetcher->get('start');
        $end   = $paramFetcher->get('end');
        $id    = $request->get('id');

        $rep      = $em->getRepository(RendezVous::class);
        $page     = intval($paramFetcher->get('page'));
        $result   = 10;
        $maxPages = ceil($rep->countByMedecin($id) / $result);

        if ($page <= 0 || $page > $maxPages) {
            $page = 1;
        }

        $limit  = $result;
        $offset = ($page - 1) * $result;

        $rendezVous = $em->getRepository(RendezVous::class)->findAllMedecinRendezVous(
            $request->get('id')
            , $start
            , $end
            , $limit
            , $offset
        );
        return ['maxPages' => $maxPages, 'results' => $rendezVous];
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function formatRequest(Request $request)
    {
        $data = $request->request->all();
        foreach ($data as $key => $value) {
            if (strpos($key, '_') !== false) {
                $data[camel_case($key)] = $value;
                unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * @Rest\View(serializerGroups={"rdv"})
     * @Rest\Post("medecins/{id}/rendez-vous")
     */
    public function postRendezVousAction(Request $request)
    {
        $em         = $this->getDoctrine()->getEntityManager();
        $repMedecin = $em->getRepository(Medecin::class);
        $repPatient = $em->getRepository(Patient::class);
        $medecin    = $repMedecin->find($request->get('id'));
        $rendezVous = new RendezVous();

        $form = $this->createForm(RendezVousType::class, $rendezVous);
        $form->submit($this->formatRequest($request));

        if ($form->isValid()) {
            $identifiant = $form->get('identifiant')->getData();
            $pin         = $form->get('pin')->getData();

            $rendezVous->setMedecin($medecin);

            $patient = $repPatient->findByPinPass($identifiant, $pin);

            if ($patient) {
                $patient = $patient[0];

                $rendezVous->setPatient($patient);
                $rendezVous->setStatutRendezVous(false);
                $rendezVous->setDateAnnulationRendezVous(null);

                $em->persist($rendezVous);

                $em->flush();

                return $rendezVous;

            } else {
                $form->addError(new FormError('Ce coupe ID/PIN n\'existe pas dans notre base de données'));
            }

        }

        return $form;

    }

    /**
     * @Rest\View(serializerGroups={"rdv"})
     * @Rest\Patch("medecins/{id}/rendez-vous/{rendezVous}")
     */
    public function patchRendezVousAction(Request $request)
    {
        $em         = $this->getDoctrine()->getEntityManager();
        $repRdv     = $em->getRepository(RendezVous::class);
        $rendezVous = $repRdv->find($request->get('rendezVous'));

        $dateRendezVous = $rendezVous->getDateRendezVous();

        $form = $this->createForm(RendezVousType::class, $rendezVous);
        $form->submit($this->formatRequest($request), false);

        if ($form->isValid()) {

            $motifAnnulationRendezVous = $form->get('motifAnnulationRendezVous')->getData();

            if ($motifAnnulationRendezVous) {
                $rendezVous->setDateAnnulationRendezVous(new \DateTime());
                $rendezVous->setStatutRendezVous(2);

                if ($contact = $rendezVous->getPatient()->getSmsContact()) {
                    /*$smsManager = new SmsManager();
                $message    = "Votre RDV du %s a été annulé: Motif: %s";
                $message    = sprintf($message, $dateRendezVous, $motifAnnulationRendezVous);
                $smsManager->send($message, $contact);*/
                }
            }

            $em->merge($rendezVous);

            $em->flush();

            return $rendezVous;

        }

        return $form;

    }
}
