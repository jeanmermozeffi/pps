<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Entity\ActionRendezVous;
use PS\GestionBundle\Entity\Consultation;
use PS\GestionBundle\Entity\Medecin;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Entity\RendezVous;
use PS\GestionBundle\Form\AnnulationRendezVousType;
use PS\GestionBundle\Form\SearchType;
use PS\UtilisateurBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormError;
use PS\UtilisateurBundle\Entity\CompteAssocie;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
//use Symfony\Component\HttpFoundatio
//n\JsonResponse;

class RendezVousController extends Controller
{
     /**
     * @return mixed
     */
    public function indexAction(Request $request, Patient $patient = null)
    {
        if ($patient) {
            $this->denyAccessUnlessGranted('ROLE_EDIT_PATIENT', $patient);
        }

        $user      = $this->getUser();
        $medecins  = [];
        $em        = $this->getDoctrine()->getManager();
        $rep       = $em->getRepository(Medecin::class);
        $idMedecin = $request->query->get('idMedecin');

        $medecin = null;

        //$personne = $user->getPersonne()->getId();

        if ($this->isGranted('ROLE_ASSISTANT')) {
            $medecins = $rep->findByMedecinByPersonne($user, true);
            if ($idMedecin) {
                $medecin = $rep->find($idMedecin);
            }
        }

       
        $associes = $em->getRepository(CompteAssocie::class)->findByPatient($patient);
        return $this->render('GestionBundle:RendezVous:index.html.twig', [
            'patient'    => $patient,
            'associes'  => $associes,
            'idMedecin' => $idMedecin,
            'medecins'  => $medecins,
            'medecin'   => $medecin,
            // ...
        ]);
        
        
    }

    /**
     * @return mixed
     */
    public function rendezVousAction(Request $request, Patient $patient = null)
    {

        $em         = $this->getDoctrine()->getManager();
        $start      = $request->get('start');
        $end        = $request->get('end');
        //$idPatient    = $request->get('id');
        $idMedecin  = $request->get('idMedecin');
        $repMedecin = $em->getRepository(Medecin::class);
        $results    = [];
        $user       = $this->getUser();
        $hopital    = null;
        $medecin    = null;

        if ($this->isGranted('ROLE_IS_MEDICAL', $user)) {
            $hopital = $user->getHopital();
        }

        //dump($user->hasRole('ROLE_INFIRMIER'), $idMedecin);exit;

        
        

       // $idPersonne = $personne->getId();
        if ($user->hasRole('ROLE_MEDECIN')) {
            $medecin = $repMedecin->findPersoByParam($this->getUser()->getPersonne()->getId());
            $id      = $medecin[0]->getId();

            $results = $em->getRepository(RendezVous::class)->findByMedecin($id, $start, $end);
        } else if ($this->isGranted('ROLE_ASSISTANT') &&
            $idMedecin && $repMedecin->isFromCurrentHospital($idMedecin, $hopital)
        ) {

            $results = $em->getRepository(RendezVous::class)->findByMedecin($idMedecin, $start, $end);
        } else {

            if (!$patient) {
                $patient = $this->getUser()->getPersonne()->getPatient();
            } else {
                $this->denyAccessUnlessGranted('ROLE_EDIT_PATIENT', $patient);
            }
            //$patient = $em->getRepository(Patient::class)->find($idPatient);
            
           
            $results = $em->getRepository(RendezVous::class)->findByPatient($patient->getId(), $start, $end);
        }

        $serializer  = \JMS\Serializer\SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($results, 'json');
        $response    = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function addAction(Request $request, Consultation $consultation = null)
    {

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $idMedecin = $request->get('idMedecin');

        $rendezVous = new RendezVous();

        if ($user->hasRole('ROLE_MEDECIN')) {
            $medecin = $em->getRepository(Medecin::class)
                            ->findOneByPersonne($this->getUser()->getPersonne());

            $rendezVous->setMedecin($medecin);
        }

        $date       = $request->query->get('d');
        $hasError   = false;
        

        if ($consultation) {
            $rendezVous->setConsultation($consultation);
        }

        $form = $this->createForm($this->get('app.rdv_type'), $rendezVous);

        $form->handleRequest($request);

        /*if ($form->has('medecin') && $idMedecin && !$form->isSubmitted()) {
            $form->get('medecin')->setData($idMedecin);
        }*/

        $typeRendezVous = 1;

        if ($form->isSubmitted() && $form->isValid()) {
            //exit;

            if (!$this->isGranted('ROLE_IS_MEDICAL', $user)) {
                $patient = $user->getPersonne()->getPatient();
                $typeRendezVous = 0;
            } else {
                if ($consultation) {
                    $patient = $consultation->getPatient();
                } else {
                    $identifiant = $form->get('identifiant')->getData();
                    $pin         = $form->get('pin')->getData();

                    $patient = $em->getRepository(Patient::class)->findByParam($identifiant, $pin);
                }
            }

            if ($patient) {

                if ($form->has('medecin') && !isset($medecin)) {
                    if (!($medecin = $form->get('medecin')->getData())) {
                        $form->add(new FormError('Veuillez sélectionner un medecin'));
                        $hasError = true;
                    }
                }

                if (!$hasError) {
                    //$medecin = is_array($medecin) ? $medecin[0] : $medecin;
                    $rendezVous->setMedecin($medecin);
                    $rendezVous->setPatient(is_array($patient) ? $patient[0]: $patient);
                    $rendezVous->setStatutRendezVous(false);
                    $rendezVous->setTypeRendezVous($typeRendezVous);
                    if ($consultation) {
                        $rendezVous->addConsultation($consultation);
                    }

                    $action = new ActionRendezVous();

                    $action->setUtilisateur($this->getUser());
                    $action->setRendezVous($rendezVous);
                    $action->setTypeAction(ActionRendezVous::ACTION_CREATE);

                    $em->persist($rendezVous);


                    $smsManager     = $this->get('app.ps_sms');
                    $message        = "Demande de RDV / Motif: %s / Heure: %s";
                    $dateRendezVous = $rendezVous->getDateRendezVous();
                    $motif          = $rendezVous->getLibRendezVous();
                    $message        = sprintf($message, $dateRendezVous->format('d-m-Y à H:i'), $motif);
                    if ($this->isGranted('ROLE_CUSTOMER')) {
                        $personne = $this->getUser()->getPersonne();
                    } else {
                        $personne = $medecin->getPersonne();
                    }

                    

                    $em->flush();

                    if (isset($personne)) {
                        $smsManager->send($message, $personne->getSmsContact());
                    }
                    

                    $this->addFlash('success', 'Le rendez-vous a été ajouté avec succès');

                    if ($consultation) {
                        return $this->redirectToRoute('admin_consultation_preview_print', ['id' => $patient->getId(), 'id1' => $consultation->getId()]);
                    }

                    return $this->redirectToRoute('admin_gestion_rdv_index', ['idMedecin' => $medecin->getId()]);
                }

            } else {
                $form->addError(new FormError('Ce coupe ID/PIN n\'existe pas dans notre base de données'));
            }
        }

        return $this->render('GestionBundle:RendezVous:add.html.twig', [
            'form'         => $form->createView(),
            'date'         => $date,

            'consultation' => $consultation,
            // ...
        ]);
    }

    /**
     * @param $id
     */
    public function detailsAction($id)
    {

    }

    /**
     * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $form = $this->createPatientForm();

        $em   = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $error      = '';
        $rendezVous = [];
        $patient    = null;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $identifiant = $form->get('identifiant')->getData();
            $pin         = $form->get('pin')->getData();

            $patient = $em->getRepository(Patient::class)->findByParam($identifiant, $pin);

            if ($patient) {
                $currentDate = new \DateTime();
                $idPatient   = $patient->getId();
                if (!$this->isGranted('ROLE_MEDECIN')) {
                    $id                = $idPatient;
                    $from              = 'patient';
                    $search['hopital'] = $user->getHopital() ? $user->getHopital()->getId() : -1;
                } else {
                    $from              = 'medecin';
                    $id                = $em->getRepository(Medecin::class)->findIdByPersonne($user->getPersonne());
                    $search['patient'] = $idPatient;
                }
                $rendezVous = $em->getRepository(RendezVous::class)
                    ->findAllPatientRendezVous(
                        $id
                        , null
                        , null
                        , null
                        , null
                        , $from
                        , $search
                    );

                //dump($rendezVous);exit;
                if (!$rendezVous) {
                    $error = 'Aucun RDV pour ce patient à partir de la date d\'aujourd\'hui';
                }
            } else {
                $error = 'Ce couple ID/PIN n\'existe pas dans notre Base de données';
            }

        }

        return $this->render('GestionBundle:RendezVous:search.html.twig', [
            'form'       => $form->createView(),
            'error'      => $error,
            'patient'    => $patient,
            'rendezVous' => $rendezVous,
        ]);
    }

    /**
     * @return mixed
     */
    private function createPatientForm()
    {
        $form = $this->createForm(new SearchType(), [
            'action' => $this->generateUrl('admin_gestion_rdv_search'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit', ['label' => 'Rechercher']);

        return $form;
    }

    /**
     * @param Request $request
     * @param RendezVous $rendezVous
     * @return mixed
     */
    public function editAction(Request $request, RendezVous $rendezVous)
    {
         $currentDate = new \DateTime();
        $builder        = $this->createFormBuilder()
            ->add('statutRendezVous', CheckBoxType::class, ['label' => 'Présence effective du patient', 'required' => false]);


        $form = $builder->getForm();

        $motifAnnulationRendezVous = $rendezVous->getMotifAnnulationRendezVous();

        $statutRendezVous = $rendezVous->getStatutRendezVous();

        $isEditable = ((!$motifAnnulationRendezVous && $statutRendezVous == 0) || $statutRendezVous == 1);

        if (!$isEditable) {
            $this->addFlash('error', 'Le rendez-vous ne peut être modifié');

            return $this->redirectToRoute('admin_gestion_rdv_index');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $statutRendezVous = $form->get('statutRendezVous')->getData();
            $em = $this->getDoctrine()->getManager();

            $rendezVous->setStatutRendezVous(boolval($statutRendezVous));

            $action = new ActionRendezVous();

            $action->setUtilisateur($this->getUser());
            $action->setRendezVous($rendezVous);
            $action->setTypeAction(ActionRendezVous::ACTION_EDIT);
            $em->persist($action);

            $em->persist($rendezVous);

            $em->flush();

            $this->addFlash('success', 'Le rendez-vous a été modifié avec succès');

            return $this->redirectToRoute('admin_gestion_rdv_index');

        }

        return $this->render('GestionBundle:RendezVous:edit.html.twig', [
            'form'       => $form->createView(),
            'rendezVous' => $rendezVous,
            'isEditable' => $isEditable,
        ]);
    }

    /**
     * @param Request $request
     * @param RendezVous $rendezVous
     * @return mixed
     */
    public function annulerAction(Request $request, RendezVous $rendezVous)
    {
        $user = $this->getUser();

        if ($rendezVous->getMotifAnnulationRendezVous()) {
            $this->addFlash('error', 'Le rendez-vous ne peut être annulé');

            return $this->redirectToRoute('admin_gestion_rdv_index');
        }

        if ($rendezVous->getTypeRendezVous() == 0 && $rendezVous->getPatient() != $user->getPersonne()->getPatient()) {
            
        }

        if ($this->isGranted('ROLE_IS_MEDICAL', $user) && ($rendezVous->getTypeRendezVous() == 0 
        && $rendezVous->getPatient()->getPersonne() != $user->getPersonne())) {
            $this->addFlash('error', 'Accès interdit');

            return $this->redirectToRoute('admin_gestion_rdv_index');
        }

        $form = $this->createForm(AnnulationRendezVousType::class, $rendezVous);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $rendezVous->setDateAnnulationRendezVous(new \DateTime());
            $rendezVous->setStatutRendezVous(RendezVous::STATUS_CANCELED);

            //$medecin = $rendezVous->getMedecin();

            $em = $this->getDoctrine()->getManager();

            $em->merge($rendezVous);

            if ($rendezVous->getTypeRendezVous() == 0) {
                $contact = $rendezVous->getPatient()->getPersonne()->getSmsContact();
                $message = "Votre RDV du %s a été annulé: Motif: %s";
            } else {
                $contact = $rendezVous->getMedecin()->getPersonne()->getSmsContact();
                $message = "Le patient a annulé le RDV du %s. Motif: %s";
            }

            if ($contact) {
                $smsManager     = $this->get('app.ps_sms');
                $dateRendezVous = $rendezVous->getDateRendezVous();
                $motif          = $rendezVous->getMotifAnnulationRendezVous();
                $message        = sprintf($message, $dateRendezVous->format('d/m/Y à H:i'), $motif);

                $smsManager->send($message, $contact);
            }

            $action = new ActionRendezVous();

            $action->setUtilisateur($this->getUser());
            $action->setRendezVous($rendezVous);
            $action->setTypeAction(ActionRendezVous::ACTION_CANCEL);

            $em->flush();

            $this->addFlash('success', 'Le rendez-vous a été annulé avec succès');

            return $this->redirectToRoute('admin_gestion_rdv_index');
        }

        return $this->render('GestionBundle:RendezVous:annuler.html.twig', [
            'form'       => $form->createView(),
            'rendezVous' => $rendezVous,
        ]);

    }

}
