<?php



namespace PS\MobileBundle\Controller\Patient;



use FOS\RestBundle\Controller\Annotations as Rest;

use FOS\RestBundle\Controller\Annotations\QueryParam;

use FOS\RestBundle\Controller\FOSRestController;

use FOS\RestBundle\Request\ParamFetcherInterface;

use PS\GestionBundle\Entity\Medecin;

use PS\GestionBundle\Entity\ActionRendezVous;

use PS\GestionBundle\Entity\RendezVous;

use PS\GestionBundle\Form\AnnulationRendezVousType;

use PS\MobileBundle\Controller\ApiTrait;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;



class RendezVousController extends FOSRestController

{

    use ApiTrait;



    /**

     * @Rest\View(serializerGroups={"rdv", "medecin", "personne", "specialite", "hopital", "info-hopital", "pays","info-patient", "photo"})

     * @Rest\Get("/patients/{id}/rendez-vous")

     * @QueryParam(name="page", requirements="\d+", default=1, description="Index de début de la pagination")

     * @QueryParam(name="limit", requirements="\d+", default=10, description="Nombre d'éléments")

     * @QueryParam(name="start")

     * @QueryParam(name="end")

     * @QueryParam(name="statut")

     */

    public function getAllRendezVousAction(Request $request, ParamFetcherInterface $paramFetcher)

    {



        $start = $paramFetcher->get('start');

        $end   = $paramFetcher->get('end');

        $id    = $request->get('id');

        $statut = $paramFetcher->get('statut');



        $rep      = $this->getRepository(RendezVous::class);

        $page     = intval($paramFetcher->get('page')) ?: 1;

        $limit    = intval($paramFetcher->get('limit')) ?: 10;

        $maxPages = ceil($rep->countByPatient($id) / $limit);



        if ($page <= 0 || $page > $maxPages) {

            $page = 1;

        }



        $offset = ($page - 1) * $limit;



        return ['maxPages' => $maxPages, 'data' => $rep->findPatientRendezVous($request->get('id'), $start, $end, $limit, $offset),  'patient' => $this->getPatient($id)];

    }



    /**

     * @Rest\View(serializerGroups={"rdv", "medecin", "personne", "specialite", "hopital", "info-hopital", "pays"})

     * @Rest\Get("/patients/{id}/rendez-vous/{rdv}")

     */

    public function getRendezVousAction(Request $request)

    {

        $rendezVous = $this->getRepository(RendezVous::class)->findOneBy(['patient' => $request->get('id'), 'id' => $request->get('rdv')]);



        if ($rendezVous) {

            return $rendezVous;

        }



        return $this->notFound('RDV avec ID ' . $request->get('rdv') . ' inexistant ou n\'est pas associé à votre compte');

    }



    /**

     * @Rest\View(serializerGroups={"rdv", "medecin", "personne", "specialite", "hopital", "info-hopital", "pays"}, statusCode=Response::HTTP_CREATED)

     * @Rest\Post("/patients/{id}/rendez-vous")

     */

    public function postRendezVousAction(Request $request)

    {

        $data       = $this->formatValue($request->request->all(), ['libelle' => 'libRendezVous', 'date' => 'dateRendezVous']);

        $patient    = $this->getPatient($request->get('id'));

        $rendezVous = new RendezVous();

        $rendezVous->setPatient($patient);

        $rendezVous->setStatutRendezVous(0);

        $rendezVous->setTypeRendezVous(0);

        $medecin = $this->getRepository(Medecin::class)->find($data['medecin']);

        $rendezVous->setMedecin($medecin);

        $rendezVous->setHopital($medecin ? $medecin->getHopital() : null);



        $form = $this->createForm($this->get('app.rdv_type'), $rendezVous, [

            'csrf_protection' => false,

            'date_format'      => 'api',

        ]);



        $form->submit($data);



        if ($form->isValid()) {

            $em = $this->getManager();

            $em->persist($rendezVous);

            $em->flush();



            return $rendezVous;

        }



        return $form;



    }



    /**

     * @Rest\View(serializerGroups={"rdv", "medecin", "personne", "specialite", "hopital", "info-hopital", "pays"})

     * @Rest\Patch("/patients/{id}/rendez-vous/{rdv}")

     */

    public function patchRendezVousAction(Request $request)

    {

        $patient = $this->getPatient($request->get('id'));

        //$data = $request->request->all();

        $rendezVous = $this->getRepository(RendezVous::class)->findOneBy(['id' => $request->get('rdv'), 'patient' => $patient]);



        if ($rendezVous) {

            $form = $this->createForm(AnnulationRendezVousType::class, $rendezVous, [

                'csrf_protection' => false,

            ]);



            $form->submit($this->formatValue($request->request->all(), ['motifAnnulation' => 'motifAnnulationRendezVous']));



            if ($form->isValid()) {

                $em = $this->getManager();



                $rendezVous->setDateAnnulationRendezVous(new \DateTime());

                $rendezVous->setStatutRendezVous(RendezVous::STATUS_CANCELED);





                $contact = $rendezVous->getMedecin()->getPersonne()->getSmsContact();

                $message = "Le patient a annulé le RDV du %s. Motif: %s";



                if ($contact) {

                    $smsManager     = $this->get('app.ps_sms');

                    $dateRendezVous = $rendezVous->getDateRendezVous();

                    $motif          = $rendezVous->getMotifAnnulationRendezVous();

                    $message        = sprintf($message, $dateRendezVous->format('d/m/Y à H:i'), $motif);



                    $smsManager->send($message, $contact);

                }



                if ($utilisateur = $patient->getUtilisateur()) {

                     $action = new ActionRendezVous();



                    $action->setUtilisateur($utilisateur);

                    $action->setRendezVous($rendezVous);

                    $action->setTypeAction(ActionRendezVous::ACTION_CANCEL);

                }



               





                $em->flush();



                return $rendezVous;

            }



            return $form;

        }



        return $this->notFound('RDV avec ID ' . $request->get('rdv') . ' inexistant ou n\'est pas associé à votre compte');



    }

}

