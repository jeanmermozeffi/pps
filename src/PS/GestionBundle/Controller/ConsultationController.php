<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Service\RowAction;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Entity\Consultation;
use PS\GestionBundle\Entity\RendezVous;
use PS\GestionBundle\Entity\ConsultationConstante;
use PS\GestionBundle\Entity\RendezVousConsultation;
use PS\GestionBundle\Entity\ConsultationInfirmier;
use PS\GestionBundle\Entity\HistoriqueConsultation;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Form\ConsultationType;
use PS\GestionBundle\Form\SearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PS\GestionBundle\Form\InfoConsultationType;
use PS\GestionBundle\Entity\InfoConsultation;
use PS\GestionBundle\Entity\PatientAntecedent;
use PS\ParametreBundle\Entity\Constante;
use PS\ParametreBundle\Entity\PatientAffections;

/**
 * Consultation controller.
 *
 */
class ConsultationController extends Controller
{

    /**
     * Lists all consultation entities.
     * @Security("is_granted('ROLE_MEDECIN')")
     */
    public function searchAction(Request $request)
    {
        $form = $this->createPatientForm();
        $form->handleRequest($request);
        $session = $request->getSession();

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $identifiant = $form->get('identifiant')->getData();

            $pin = $form->get('pin')->getData();

            $count = $em->getRepository('GestionBundle:Patient')->findByPass($identifiant, $pin);

            //var_dump($count[0][1]);die();
            if ($count[0][1] > 0) {
                $patient = $em->getRepository('GestionBundle:Patient')->findByParam($identifiant, $pin);

                if (!$patient) {
                    $this->addFlash(
                        'patient',
                        'Ce patient n\'existe pas dans la base de données!'
                    );
                } else {
                    $user = $this->getUser();
                    $assurance = $user->getAssurance();
                    /**
                     * @todo: A prendre pour le medecin conseil
                     */
                    if ($contact = $patient->getPersonne()->getSmsContact()) {

                        $smsManager = $this->get('app.ps_sms');
                        $message    = "Votre historique médical est entrain d'être consulté par le Medecin %s";

                        if ($assurance) {
                            $message .= ", conseil de l'assurance %s";
                        } else {
                            $message .= " de l'hopital ou du centre de Santé %s";
                        }

                        $user       = $this->getUser();
                        $personne   = $user->getPersonne();
                        $nom        = $user->getUsername();
                        $hopital    = $user->getHopital();

                        if ($personne->getNomComplet()) {
                            $nom .= '(' . $personne->getNomComplet() . ')';
                        }

                        $label = '';

                        if($assurance)
                        {
                            $label = $assurance->getNom();
                        }
                        
                        if($hopital)
                        {
                            $label = $hopital->getNom();

                        }
                        $smsManager->send(sprintf($message, $nom, $label), $contact);

                    }

                    $session->set('patient', $patient->getId());

                    if ($assurance) {
                        $route = $this->generateUrl('admin_consultation_liste');
                    } else {
                        $route = $this->generateUrl('admin_consultation_new');
                    }


                    if ($patient->getAssocies()->count()) {
                        $session->set('patient_url_action', $route);
                        return $this->redirectToRoute('admin_gestion_patient_associe');
                    }

                    return $this->redirect($route);
                }
            } else {
                $this->addFlash(
                    'patient',
                    'Ce patient n\'existe pas dans la base de données!'
                );
            }
        }

        return $this->render('consultation/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }


     /**
     * @return mixed
     */
    public function getUploadDir($path, $key = 'data_dir', $create = false)
    {
        $path = $this->getParameter($key) . '/' . $path;
        if ($create && !is_dir($path)) {
            mkdir($path, 0777, true);
        }
        return $path;
    }

    /**
     * Lists all consultation entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $consultations = $em->getRepository('GestionBundle:Consultation')->findAll();

        return $this->render('consultation/index.html.twig', [
            'consultations' => $consultations,
        ]);
    }

    /**
     * @param Request $request
     * @param Patient $patient
     * @return mixed
     */
    public function ordonnanceAction(Request $request, Consultation $consultation, Patient $patient = null)
    {

        $em       = $this->getDoctrine()->getManager();
        $personne = $this->getUser()->getPersonne()->getId();
        if (!$patient) {
            $patient = $em->getRepository(Patient::class)->findOneByPersonne($personne);
        }

        if (!$patient) {
            throw $this->createAccessDeniedException();
        }

        if ($consultation->getPatient() != $patient) {
            throw $this->createAccessDeniedException();
        }

        $medicaments = $consultation->getMedicaments();


        return $this->render('consultation/ordonnance.html.twig', [

            'patient'      => $patient,
            'medicaments' => $medicaments,

            'consultation' => $consultation,
            //'pharmacies' => $pharmacies
        ]);
    }


    public function getCurrentPatient(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository(Patient::class)->find($request->getSession()->get('patient'));
    }




    /**
     * Creates a new consultation entity.
     * @Security("is_granted('ROLE_MEDECIN')")
     */
    public function newAction(Request $request, Patient $patient = null)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $patient = $patient ? $patient : $this->getCurrentPatient($request);
        if (!$patient) {
            return $this->generateUrl('admin_consultation_search');
        }
        $consultation = new Consultation();

        $repPatientAff = $em->getRepository(PatientAffections::class);

        $constantes = $em->getRepository(Constante::class)->findAll();
        foreach ($constantes as $constante) {
            $consultation->addConstante((new ConsultationConstante)->setConstante($constante));
        }


        $form  = $this->createCreateForm($consultation, $patient, false);

        $medecin = $em->getRepository('GestionBundle:Medecin')->findOneByPersonne($this->getUser()->getPersonne());

        $consultation->setPatient($patient);
        $consultation->setMedecin($medecin);
        $consultation->setHopital($medecin->getHopital());
        $consultation->setRefConsultation($this->get('app.psm_util')->random(8));
        $consultation->setPrixConsultation(0);

        $form->handleRequest($request);

        if ($request->isMethod('POST')) {

            if ($form->isSubmitted() && $form->isValid()) {

                $fonctionnels = $form->get('fonctionnels')->getData();
                $physiques = $form->get('physiques')->getData();
                $personnels = $form->get('personnels')->getData();
                $familiaux = $form->get('familiaux')->getData();
                $affections = $form->get('affections')->getData();

                foreach ($fonctionnels as $signe) {
                    $consultation->addSigne($signe);
                }

                foreach ($physiques as $signe) {
                    $consultation->addSigne($signe);
                }

                foreach ($personnels as $antecedent) {
                    $consultation->addAntecedent($antecedent);
                }

                foreach ($familiaux as $antecedent) {
                    $consultation->addAntecedent($antecedent);
                }


                /*foreach ($consultation->getAntecedents() as $antecedent) {
                    $_antecedent = new PatientAntecedent();
                    $_antecedent->setAntecedent($antecedent->getAntecedent());
                    $_antecedent->setType($antecedent->getType());
                    $_antecedent->setGroupe($antecedent->getAntecedent()->getGroupe());
                    $patient->addLigneAntecedent($_antecedent);
                }*/

                foreach ($affections as $affection) 
                {
                    $nomAffection = $affection->getAffection();
                    if (!$repPatientAff->findOneByAffection($nomAffection)) {
                        $patientAffection = new PatientAffections();
                        $patientAffection->setAffection($nomAffection);
                        $patientAffection->setDate(new \DateTime());
                        $patientAffection->setCommentaire('');
                        $patientAffection->setVisible(1);
                        $patient->addAffection($patientAffection);
                    }
                }


                $em = $this->getDoctrine()->getManager();

                $action = new HistoriqueConsultation();
                $action->setLibHistorique(HistoriqueConsultation::ACTION_CREATE);
                $action->setUtilisateur($this->getUser());
                $consultation->addAction($action);

                $em->persist($consultation);
                $em->persist($patient);
                $em->flush();

                $this->addFlash(
                    'message',
                    'Consultation enregistrée avec succès!'
                );

                $this->get('app.action_logger')
                    ->add('Nouvelle consultation', $patient);

                return $this->redirectToRoute('admin_consultation_preview_print', ['id' => $patient->getId(), 'id1' => $consultation->getId()]);
            }
        }

        return $this->render('consultation/new.html.twig', [
            'consultation' => $consultation,
            'patient'      => $patient,
            'constantes' => $constantes,
            'form'         => $form->createView(),
        ]);
    }


    public function infoAction(Request $request, Patient $patient, Consultation $consultation)
    {
        if ($consultation->getAssurance() != $this->getUser()->getAssurance()) {
            $this->addFlash('warning', 'Vous ne pouvez pas accéder à cette page');
            return $this->redirectToRoute('admin_consultation_liste', ['id' => $patient->getId()]);
        }
        $info = new InfoConsultation();
        $info->setConsultation($consultation);
        $info->setMedecin($this->getUser()->getPersonne()->getMedecin());
        $form = $this->createForm(InfoConsultationType::class, $info);

        $form->add('submit', 'submit', ['label' => 'Enregistrer']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($info);
            $em->flush();

            $this->addFlash('success', 'Opération effectuée avec succès');
            return $this->redirectToRoute('admin_consultation_liste_infos', ['patieent' => $patient->getId(), 'consultation' => $consultation->getId()]);
        }

        return $this->render('consultation/info.html.twig', [
            'consultation' => $consultation,
            'patient'      => $patient,
            'form'         => $form->createView(),
        ]);
    }

    /**
     * Lists all consultation entities.
     *
     */
    public function listeAction(Request $request, Patient $patient = null)
    {
        $patient = $patient ? $patient : $this->getCurrentPatient($request);
        if (!$patient) {
            return $this->generateUrl('admin_consultation_search');
        }
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $liste_consultations = $em->getRepository('GestionBundle:Consultation')->findByPatient($patient->getId(), $user);

        $consultations = $this->get('knp_paginator')->paginate(
            $liste_consultations,
            $request->query->get('page', 1) /*page number*/,
            5/*limit per page*/
        );

        return $this->render('consultation/liste.html.twig', [
            'consultations' => $consultations,
            'patient'       => $patient,
            'user' => $user
        ]);
    }

    /**
     * @param Request $request
     * @param Consultation $consultation
     * @return mixed
     */
    public function actionsAction(Request $request, Consultation $consultation)
    {
        $actions = $consultation->getActions();
        $infos = $consultation->getInfos();

        return $this->render('consultation/actions.html.twig', [
            'consultation' => $consultation,
            'actions'      => $actions,
            'infos' => $infos
        ]);
    }


    /**
     * @param Request $request
     * @param Consultation $consultation
     * @return mixed
     */
    public function listeInfosAction(Request $request, Consultation $consultation, Patient $patient)
    {

        if ($consultation->getAssurance() != $this->getUser()->getAssurance()) {
            $this->addFlash('warning', 'Vous ne pouvez pas accéder à cette page');
            return $this->redirectToRoute('admin_consultation_liste', ['id' => $patient->getId()]);
        }

        $liste_infos = $consultation->getInfos();

        $infos = $this->get('knp_paginator')->paginate(
            $liste_infos,
            $request->query->get('page', 1) /*page number*/,
            5/*limit per page*/
        );

        return $this->render('consultation/liste_info.html.twig', [
            'consultation' => $consultation,
            'infos' => $infos,
            'patient' => $patient
        ]);
    }



    /**
     * Creates a new consultation entity.
     * @Security("is_granted('ROLE_MEDECIN')")
     */
    public function rendezVousAction(Request $request, Consultation $consultation)
    {
        $rendezvous = new RendezVous();
        $rendezvous->setConsultation($consultation);

        $form = $this->createForm($this->get('app.rdv_type'), $rendezvous, [
            'action' => $this->generateUrl('admin_gestion_rdv_add', ['consultation' => $consultation->getId()])
        ]);

        $form->handleRequest($request);


        return $this->render('consultation/rdv.html.twig', [
            'consultation' => $consultation,
            'form' => $form->createView(),
            'patient' => $consultation->getPatient()

        ]);
    }



    /**
     * Lists all consultation entities.
     *
     */
    public function historiqueAction(Request $request)
    {
        $em         = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('GestionBundle:Consultation');
        $source     = new Entity('GestionBundle:Consultation');
        $user       = $this->getUser();
        $id         = $user->getPersonne()->getId();
        $statut     = $request->get('statut');


        $grid = $this->get('grid');

        $source->manipulateQuery(function ($qb) use ($user, $id, $statut) {

            if ($user->hasRole('ROLE_MEDECIN')) {
                $qb->andWhere("_medecin_personne.id = {$id}");
            } else {
                $qb->andWhere("_patient_personne.id = {$id}");
            }

            if ($statut) {
                $qb->andWhere('_a.statut = :statut');
                $qb->setParameter('statut', $statut);
            }

            $qb->orderBy('_a.dateConsultation', 'DESC');
        });

        $grid->setSource($source);

        $rowAction = new RowAction('Détails', 'admin_consultation_show');


        $rowAction->manipulateRender(function ($action, $row) {

            $action->setRouteParameters([
                'id1' => $row->getField('id'), 'id' => $row->getField('patient.id'),
            ]);
            $action->setAttributes(['class' => 'btn btn-default btn-sm']);
            $action->setTitle('<i class="fa fa-list-alt"></i>');
            return $action;
        });
        $grid->addRowAction($rowAction);

        /*if ($user->hasRole('ROLE_MEDECIN')) {
            $rowAction = new RowAction('Modifier', 'admin_consultation_edit');
            $rowAction->manipulateRender(function ($action, $row) {
                if (!$this->isGranted('ROLE_EDIT_CONSULTATION', $row->getEntity())) {
                    return;
                }
                $action->setRouteParameters([
                    'id1' => $row->getField('id')
                    , 'id' => $row->getField('patient.id'),
                ]);
                $action->setAttributes(['class' => 'btn btn-warning btn-sm']);
                $action->setTitle('<i class="fa fa-edit"></i>');
                return $action;
            });
            $grid->addRowAction($rowAction);
        }*/

        $rowAction = new RowAction('Imprimer', 'admin_consultation_preview_print');
        $rowAction->manipulateRender(function ($action, $row) {
            if ($row->getField('statut') != -1) {
                $action->setRouteParameters([
                    'id1' => $row->getField('id'), 'id' => $row->getField('patient.id'),
                ]);
                $action->setAttributes(['class' => 'btn btn-success btn-sm']);
                $action->setTitle('<i class="fa fa-print"></i>');
                return $action;
            }
        });
        $grid->addRowAction($rowAction);


        /*$rowAction = new RowAction('RDV', 'admin_consultation_rdv');

        $rowAction->manipulateRender(function ($action, $row) {
            if ($row->getField('statut') != -1) {
                $action->setRouteParameters([
                    'id' => $row->getField('id')
                ]);
                $action->setAttributes(['class' => 'btn bg-black btn-sm']);
                $action->setTitle('<i class="fa fa-calendar"></i>');
                return $action;
            }

        });

        $grid->addRowAction($rowAction);*/

        $grid->getColumn('statut')->manipulateRenderCell(function ($value) {
            if ($value == -1) {
                return '<span class="label label-default">En attente du medecin</span>';
            }
            return '<span class="label label-success">Validé</span>';
        });

        //$grid->getColumn('statut')

        //$grid->getColumn('statut')->setSize();
        //$grid->getColumn('dateConsultation')->setSize(20);
        $grid->getColumn('patient_nom_complet')->setVisible($user->hasRole('ROLE_MEDECIN'));
        $grid->getColumn('medecin.hopital.nom')->setVisible($user->hasRole('ROLE_CUSTOMER') || $user->hasRole('ROLE_ADMIN'));
        $grid->getColumn('medecin_nom_complet')->setVisible($user->hasRole('ROLE_CUSTOMER') || $user->hasRole('ROLE_ADMIN'));

        return $grid->getGridResponse('consultation/historique_grid.html.twig', compact('role'));
    }

    /**
     * Creates a form to create a Consultation entity.
     *
     * @param Consultation $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Consultation $entity, Patient $patient, $edit = true, $options = [])
    {
        $defaultOptions = [
            'action' => $this->generateUrl('admin_consultation_new', ['id' => $patient->getId()]),
            'method' => 'POST',
            'patient' => $patient,
            'edit' => $edit,
            'doc_options' => [
                'folder' => '../data/',
                'mime_types' => ['application/pdf', 'image/png', 'image/jpeg', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
                'upload_dir' => $this->getUploadDir('consultations')
            ]
        ];

        $options = array_merge($defaultOptions, $options);
        $form = $this->createForm($this->get('app.consultation_type'), $entity, $options);

        $form->add('submit', 'submit', ['label' => 'Enregistrer']);

        return $form;
    }

    /**
     * @return mixed
     */
    private function createPatientForm()
    {
        $form = $this->createForm(SearchType::class, [
            'action' => $this->generateUrl('admin_consultation_search'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit', ['label' => 'Rechercher']);

        return $form;
    }

    /**
     *
     * @param Consultation $consultation The entity
     *
     * @return \Symfony\Component\Form\Form The form
     *
     */
    public function showAction(Request $request, Patient $patient, $id1)
    {

        $em           = $this->getDoctrine()->getManager();
        $consultation = $em->getRepository('GestionBundle:Consultation')->find($id1);

        $constantes = $em->getRepository(Constante::class)->findAll();

        $form         = $this->createCreateForm($consultation, $patient);


        $fonctionnels  = $consultation->getSignes()->filter(function ($signe) {
            return $signe->getType() == 'fonctionnel';
        });


        $physiques  = $consultation->getSignes()->filter(function ($signe) {
            return $signe->getType() == 'physique';
        });


        $personnels  = $consultation->getAntecedents()->filter(function ($signe) {
            return $signe->getType() == 'personnel';
        });


        $familiaux  = $consultation->getAntecedents()->filter(function ($signe) {
            return $signe->getType() == 'familial';
        });


        $form->get('fonctionnels')->setData($fonctionnels);
        $form->get('physiques')->setData($physiques);

        $form->get('personnels')->setData($personnels);
        $form->get('familiaux')->setData($familiaux);

        return $this->render('consultation/show.html.twig', [
            'consultation' => $consultation,
            'patient'      => $patient,
            'constantes' => $constantes,
            'form' => $form->createView()
        ]);
    }

    /**
     *
     * @param Consultation $consultation The entity
     *
     * @return \Symfony\Component\Form\Form The form
     *
     */
    public function historiqueShowAction(Patient $patient, $id1)
    {
        $em           = $this->getDoctrine()->getManager();
        $consultation = $em->getRepository('GestionBundle:Consultation')->find($id1);

        if (!$consultation) {
            $this->addFlash('not_found', "Enregistrement introuvable");
            throw $this->createNotFoundException('Unable to find Consultation entity.');
        }

        return $this->render('consultation/historique_show.html.twig', [
            'consultation' => $consultation,
            'patient'      => $patient,
        ]);
    }

    /**
     * @Security("is_granted('ROLE_MEDECIN')")
     * Displays a form to edit an existing consultation entity.
     */
    public function editAction(Request $request, Patient $patient, $id1)
    {
        $em           = $this->getDoctrine()->getManager();
        $consultation = $em->getRepository('GestionBundle:Consultation')->find($id1);


        $constantes = $em->getRepository(Constante::class)->findAll();


        if (!$consultation) {
            throw $this->createNotFoundException('Unable to find Consultation entity.');
        }

        $this->denyAccessUnlessGranted('ROLE_EDIT_CONSULTATION', $consultation);

        $editForm = $this->createEditForm($patient, $consultation);
        $editForm->handleRequest($request);


        foreach ($consultation->getSignes() as $signe) {
            $consultation->removeSigne($signe);
        }


        foreach ($consultation->getAntecedents() as $antecedent) {
            $consultation->removeSigne($antecedent);
        }

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $fonctionnels = $editForm->get('fonctionnels')->getData();
            $physiques = $editForm->get('physiques')->getData();

            $personnels = $editForm->get('personnels')->getData();
            $familiaux = $editForm->get('familiaux')->getData();

            $affections = $editForm->get('affections')->getData();


            foreach ($fonctionnels as $signe) {
                $consultation->addSigne($signe);
            }


            foreach ($physiques as $signe) {
                $consultation->addSigne($signe);
            }


            foreach ($personnels as $antecedent) {
                $consultation->addAntecedent($antecedent);
            }


            foreach ($familiaux as $antecedent) {
                $consultation->addAntecedent($antecedent);
            }




            foreach ($affections as $affection) {
                if (!$repPatientAff->findOneByAffection($affection)) {
                    $patientAffection = new PatientAffections();
                    $patientAffection->setAffection($affection);
                    $patientAffection->setDate(new \DateTime());
                    $patientAffection->setCommentaire('');
                    $patientAffection->setVisible(1);
                    $patient->addAffection($patientAffection);
                }
            }





            $repConsInf = $em->getRepository(ConsultationInfirmier::class);

            $action = new HistoriqueConsultation();
            if ($repConsInf->findOneByConsultation($consultation) && $consultation->getStatut() != 1) {
                $libAction = HistoriqueConsultation::ACTION_EDIT_AFTER_CONSTANTE;
            } else {
                $libAction = HistoriqueConsultation::ACTION_EDIT;
            }
            $consultation->setStatut(1);
            $action->setLibHistorique($libAction);
            $action->setUtilisateur($this->getUser());
            $consultation->addAction($action);

            $em->persist($patient);
            $em->persist($consultation);
            //$consultation->setDatetModifiation(new \DateTime());
            $em->flush();

            $this->addFlash(
                'edit_consultation',
                'Consultation modifiée avec succès!'
            );

            return $this->redirectToRoute('admin_consultation_show', ['id1' => $consultation->getId(), 'id' => $patient->getId()]);
        }

        return $this->render('consultation/edit.html.twig', [
            'consultation' => $consultation,
            'patient'      => $patient,
            'form'    => $editForm->createView(),
            'constantes' => $constantes
            //'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Creates a form to create a Consultation entity.
     *
     * @param Consultation $consultation The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Patient $patient, Consultation $consultation)
    {
        $form = $this->createForm($this->get('app.consultation_type'), $consultation, [
            'action' => $this->generateUrl('admin_consultation_edit', ['id' => $patient->getId(), 'id1' => $consultation->getId()]),
            'method' => 'POST',
            'patient' => $consultation->getPatient()
        ]);

        $form->add('submit', 'submit', ['label' => 'Modifier']);

        return $form;
    }

    /**
     * Deletes a consultation entity.
     *
     */
    public function deleteAction(Request $request, Consultation $consultation)
    {
        $this->denyAccessUnlessGranted('view', $consultation);
        $form = $this->createDeleteForm($consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($consultation);
            $em->flush();
        }

        return $this->redirectToRoute('admin_consultation_index');
    }

    /**
     * Creates a form to delete a consultation entity.
     *
     * @param Consultation $consultation The consultation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Consultation $consultation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_consultation_delete', ['id' => $consultation->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /*
     * ---------------------------- zone d'impression --------------------------------------------
     */

    /**
     * @param Patient $patient
     * @param $id1
     * @return mixed
     */
    public function previewConsultationAction(Patient $patient, $id1)
    {
        $em            = $this->getDoctrine()->getManager();
        $consultation  = $em->getRepository('GestionBundle:Consultation')->find($id1);
        return $this->render('consultation/preview_consultation.html.twig', [
            "id" => $id1
            , "patient" => $patient
            , "consultation" => $consultation
        ]);
    }

    /*
     * Impression Fiche de Consultation
     */
    /**
     * @param $id
     */
    public function imprimerConsultationAction($id)
    {
        $em            = $this->getDoctrine()->getManager();
        $consultation  = $em->getRepository('GestionBundle:Consultation')->find($id);
        $signaturePath = $this->getParameter('signature_dir') . '/';


        $this->get('app.action_logger')
            ->add('Consultation du fichier de consultation #' . $id, $consultation->getPatient());


        $vars = ['consultation' => $consultation, 'signaturePath' => $signaturePath];



        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'orientation' => $options['orientation'] ?? 'L',
            'mode' => 'utf-8',
            'fontDir' => array_merge($fontDirs, [
                $options['fontDir'] ?? []
            ]),
            'fontdata' => $fontData + [
                'comfortaa' => [
                    'B' => 'Comfortaa-Bold.ttf',
                    'R' => 'Comfortaa-Regular.ttf',
                    'L' => 'Comfortaa-Light.ttf',
                ]
            ],
        ]);

        $mpdf->shrink_tables_to_fit = 1;



        $mpdf->WriteHTML($this->renderView('consultation/print_consultation.html.twig', $vars));



        $mpdf->Output(null, 'I');

        //on stocke la vue à convertir en PDF, en n'oubliant pas les paramètres twig si la vue comporte des données dynamiques
        /*$html = $this->renderView('consultation/print_consultation.html.twig', ['consultation' => $consultation, 'signaturePath' => $signaturePath]);

        //on appelle le service html2pdf
        $html2pdf = $this->get('html2pdf_factory')->create();
        //real : utilise la taille réelle
        $html2pdf->pdf->SetDisplayMode('default');
        //writeHTML va tout simplement prendre la vue stockée dans la variable $html pour la convertir en format PDF
        $html2pdf->writeHTML($html);
        //Output envoit le document PDF au navigateur internet
        $html2pdf->Output('Consultation-' . $id . '.pdf');*/

        return new Response();
    }

    /**
     * @param Patient $patient
     * @param $id1
     * @return mixed
     */
    public function previewOrdonnanceAction(Patient $patient, $id1)
    {
        return $this->render('consultation/preview_ordonnance.html.twig', ["id" => $id1, "patient" => $patient]);
    }

    /*
     * Impression Ordonnance
     */
    /**
     * @param $id
     */
    public function imprimerOrdonnanceAction($id)
    {
        $em           = $this->getDoctrine()->getManager();
        $consultation = $em->getRepository('GestionBundle:Consultation')->find($id);

        //$age = date_diff(new DateTime(), $consultation->getPatient()->getDateNaissance());

        //on stocke la vue à convertir en PDF, en n'oubliant pas les paramètres twig si la vue comporte des données dynamiques
        $html = $this->renderView('consultation/print_ordonnance.html.twig', ['consultation' => $consultation]);

        //on appelle le service html2pdf
        $html2pdf = $this->get('html2pdf_factory')->create('P', 'A5');
        //real : utilise la taille réelle
        $html2pdf->pdf->SetDisplayMode('default');
        //writeHTML va tout simplement prendre la vue stockée dans la variable $html pour la convertir en format PDF
        $html2pdf->writeHTML($html);
        //Output envoit le document PDF au navigateur internet
        $html2pdf->Output('Ordonnance.pdf');

        return new Response();
    }

    /**
     * @param Patient $patient
     * @param $id1
     * @return mixed
     */
    public function previewAnalyseAction(Patient $patient, $id1)
    {
        return $this->render('consultation/preview_consultation.html.twig', ["id" => $id1, "patient" => $patient]);
    }

    /*
     * Impression Fiche d'Analyse
     */
    /**
     * @param $id
     */
    public function imprimerAnalyseAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entities  = $em->getRepository('ParametreBundle:Demande')->find($id);
        $structure = $em->getRepository('ParametreBundle:Structure')->find(1);

        $manager = $this->container->get('doctrine')->getEntityManager();
        $query   = $manager->createQueryBuilder();

        //on stocke la vue à convertir en PDF, en n'oubliant pas les paramètres twig si la vue comporte des données dynamiques
        $html = $this->renderView('ParametreBundle:Demande:demande.html.twig', [
            'entities' => $entities,
            'structure'                                                                        => $structure
        ]);

        //on appelle le service html2pdf
        $html2pdf = $this->get('html2pdf_factory')->create();
        //real : utilise la taille réelle
        $html2pdf->pdf->SetDisplayMode('default');
        //writeHTML va tout simplement prendre la vue stockée dans la variable $html pour la convertir en format PDF
        $html2pdf->writeHTML($html);
        //Output envoit le document PDF au navigateur internet
        $html2pdf->Output('Demande-' . $id . '.pdf');

        return new Response();
    }
}
