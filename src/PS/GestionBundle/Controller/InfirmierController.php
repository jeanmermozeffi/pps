<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Service\RowAction;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Entity\Consultation;
use PS\GestionBundle\Entity\ConsultationConstante;
use PS\ParametreBundle\Entity\Constante;
use PS\GestionBundle\Entity\ConsultationInfirmier;
use PS\GestionBundle\Entity\Infirmier;
use PS\GestionBundle\Entity\InfirmierMedecin;
use PS\GestionBundle\Entity\Medecin;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Form\CompteInfirmierType;
use PS\GestionBundle\Entity\HistoriqueConsultation;
use PS\GestionBundle\Form\InfirmierType;
use PS\GestionBundle\Form\SearchType;
use PS\ParametreBundle\Entity\Hopital;
use PS\UtilisateurBundle\Entity\Personne;
use PS\UtilisateurBundle\Entity\Utilisateur;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class InfirmierController extends Controller
{
    /**
     * @return mixed
     */
    public function indexAction()
    {
        $source = new Entity(Infirmier::class);
        $grid   = $this->get('grid');

        $user = $this->getUser();
        // $id   = $user->getPersonne()->getMedecin()->getId();


        $grid->setSource($source);

        $rowAction = new RowAction('Détails', 'admin_gestion_infirmier_show');
        $rowAction->addManipulateRender(function ($action, $row) {
            return ['controller' => 'GestionBundle:Infirmier:show', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_gestion_infirmier_edit');

        $rowAction->addManipulateRender(function ($action, $row) {
            return ['controller' => 'GestionBundle:Infirmier:edit', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'admin_gestion_infirmier_delete');
        $rowAction->addManipulateRender(function ($action, $row) {
            return ['controller' => 'GestionBundle:Infirmier:delete', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        return $grid->getGridResponse('GestionBundle:Infirmier:grid.html.twig');
    }

    /**
     * Finds and displays a medecin entity.
     *
     */
    public function showAction(Infirmier $infirmier)
    {

        $showForm = $this->createForm(InfirmierType::class, $infirmier);

        $deleteForm = $this->createDeleteForm($infirmier);

        return $this->render('GestionBundle:Infirmier:show.html.twig', [
            'infirmier'   => $infirmier,
            'show_form'   => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Infirmier $infirmier
     * @return mixed
     */
    public function compteAction(Request $request, Infirmier $infirmier)
    {
        return $this->render('GestionBundle:Infirmier:compte.html.twig', [
            'infirmier' => $infirmier,
            'form'      => $form->createView(),
        ]);
    }


    /**
     * @param Request $request
     * @param Infirmier $infirmier
     * @return mixed
     */
    public function infoAction(Request $request, Infirmier $infirmier)
    {
        $form = $this->createForm(InfirmierType::class, $infirmier, [
            'action' => $this->generateUrl('admin_gestion_infirmier_info', ['id' => $infirmier->getId()]),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);



        if ($form->isSubmitted()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_gestion_infirmier_index');
        }

        return $this->render('GestionBundle:Infirmier:info.html.twig', [
            'form'      => $form->createView(),
            'infirmier' => $infirmier,
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function newAction(Request $request, Infirmier $infirmier)
    {
        $form = $this->createForm(InfirmierType::class, $infirmier, [
            'action' => $this->generateUrl('admin_gestion_infirmier_new'),
            'method' => 'POST',
        ]);

        $errors = [];

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $response['success'] = 1;
                $response['message'] = 'Enregistrement crée avec succès';
            } else {
                $errors              = $this->get('app.form_error')->all($form);
                $response['success'] = 0;
                $response['message'] = $errors;
            }

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse($response);
            } elseif ($errors) {
                $this->addFlash('infirmier_new_errors', $errors);
            }

            return $this->redirectToRoute('admin_gestion_infirmier_index');
        }

        return $this->render('GestionBundle:Infirmier:new.html.twig', [
            'infirmier' => $infirmier,
            'form'      => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Infirmier $infirmier
     * @return mixed
     */
    public function editAction(Request $request, Infirmier $infirmier)
    {
        $editForm = $this->createForm(InfirmierType::class, $infirmier, [
            'action' => $this->generateUrl('admin_gestion_infirmier_edit', ['id' => $infirmier->getId()]),
            'method' => 'POST',
        ]);

        $editForm->handleRequest($request);

        //$infirmier->getMedecin()->getPersonne()->setDateNaissance(new \DateTime());

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_gestion_infirmier_index');
        }

        return $this->render('GestionBundle:Infirmier:edit.html.twig', [
            'infirmier' => $infirmier,
            'edit_form' => $editForm->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Infirmier $infirmier
     * @return mixed
     */
    public function deleteAction(Request $request, Infirmier $infirmier)
    {
        $form = $this->createDeleteForm($infirmier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($infirmier);
            $em->flush();

            return $this->redirectToRoute('admin_gestion_infirmier_index');
        }

        return $this->render('GestionBundle:Infirmier:delete.html.twig', [
            'infirmier' => $infirmier,
            //'edit_form' => $editForm->createView(),
            'form'      => $form->createView(),
        ]);
    }

    /**
     * @return mixed
     */
    private function getInfirmier()
    {
        $em = $this->getDoctrine()->getManager();
        //$medecin = $em->getRepository(Medecin::class)->findByUser($this->getUser());
        return $em->getRepository(Infirmier::class)->findOneByPersonne($this->getUser()->getPersonne());
    }

    /**
     * @return mixed
     * @Security("is_granted('ROLE_INFIRMIER')")
     */
    public function consultationAction(Request $request, Patient $patient, Consultation $consultation = null)
    {
        $user = $this->getUser();
        $infirmier = $this->getInfirmier();
        $edit      = false;
        $direct = $request->query->get('direct');
        $em = $this->getDoctrine()->getManager();
        $constantes = $em->getRepository(Constante::class)->findAll();
        if (is_null($consultation)) {
            $consultation = new Consultation();
            $consultation->setDateConsultation(new \DateTime());
            $consultation->setRefConsultation($this->get('app.psm_util')->random(8));



            foreach ($constantes as $constante) {
                $consultation->addConstante((new ConsultationConstante)->setConstante($constante));
            }
        } else {
            $edit = ($consultation->getStatut() == -1);
        }

        $title = 'constantes.new';

        if ($edit && $consultation->getId()) {
            $title = 'constantes.details';
        }

        $consulationInfirmier = new ConsultationInfirmier();
        $form                 = $this->createForm($this->get('app.constance_type'), $consultation, [
            'action' => $this->generateUrl('admin_gestion_infirmier_consultation', ['id' => $patient->getId()]),
            'method' => 'POST',
            'patient' => $patient
        ]);

        $form->add('submit', 'submit', ['label' => 'btn.save']);

        $consultation->setPatient($patient);
        $consultation->setStatut(-1);
        $consultation->setDiagnostique('');
        $consultation->setMotif('');
        $consultation->setDiagnostiqueFinal('');
        $consultation->setHopital($user->getHopital());
        $consultation->setPrixConsultation(0);
        $consultation->setObservation('');


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $medecin = $form->get('medecin')->getData();

            $personne = $patient->getPersonne();
            $nom = $personne->getNom()[0];
            $prenom = $personne->getPrenom();


            $smsManager  = $this->get('app.ps_sms');
            $smsManager->send(sprintf("Bonjour, Vous avez un patient (%s %s) en attente\nPASS MOUSSO", $nom, $prenom),  $medecin->getPersonne()->getContact());

            $action = new HistoriqueConsultation();
            $action->setLibHistorique($edit ? HistoriqueConsultation::ACTION_CONSTANTE_EDIT : HistoriqueConsultation::ACTION_CONSTANTE_CREATE);
            $action->setUtilisateur($this->getUser());
            $consultation->addAction($action);


            if (!$edit) {
                $em->persist($consultation);

                $consultationInfirmier = new ConsultationInfirmier();
                $consultationInfirmier->setConsultation($consultation);
                $consultationInfirmier->setInfirmier($infirmier);
                $em->persist($consultationInfirmier);
            }



            $em->flush();


            
            $this->addFlash(
                'message',
                $this->get('translator')->trans('constantes.save_successfully')
            );

            return $this->redirectToRoute('admin_gestion_infirmier_historique');
        }

        return $this->render('GestionBundle:Infirmier:consultation.html.twig', [
            'patient'      => $patient,
            'consultation' => $consultation,
            'constantes' => $constantes,
            'title'        => $title,
            'form'         => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_INFIRMIER')")
     * @param Request $request
     * @return mixed
     */
    public function patientAction(Request $request)
    {
        $form = $this->createPatientForm();
        $form->handleRequest($request);

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
                    return $this->redirectToRoute('admin_gestion_infirmier_consultation', ['id' => $patient[0]->getId()]);
                }
            } else {
                $this->addFlash(
                    'patient',
                    'Ce patient n\'existe pas dans la base de données!'
                );
            }
        }

        return $this->render('GestionBundle:Infirmier:patient.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_INFIRMIER')")
     * @param Request $request
     * @return mixed
     */
    public function historiqueAction(Request $request)
    {
        $em           = $this->getDoctrine()->getManager();
        $infirmier    = $this->getInfirmier();
        $consulations = $em->getRepository(Consultation::class)->findAllInfirmier($infirmier, false);

        $source = new Entity(Consultation::class);
        $grid   = $this->get('grid');

        $source->initQueryBuilder($consulations);

        $grid->setSource($source);

        $grid->getColumn('statut')->manipulateRenderCell(function ($value) {
            if ($value == -1) {
                return '<span class="label label-default">En attente du médecin</span>';
            }
            return '<span class="label label-success">Validé</span>';
        });

        $rowAction = new RowAction('Modifier', 'admin_gestion_infirmier_consultation');
        $rowAction->manipulateRender(function ($action, $row) {
            if ($row->getField('statut') == -1) {
                $action->setRouteParameters([
                    'id' => $row->getField('patient.id'), 'consultation' => $row->getField('id'),
                ]);
                $action->setAttributes(['class' => 'btn btn-info btn-sm']);
                $action->setTitle('<i class="fa fa-edit"></i>');
                return $action;
            }
        });
        $grid->addRowAction($rowAction);

        //$grid->getColumn('statut')->setSafe(false);
        $grid->getColumn('diagnostique')->setVisible(false);
        $grid->getColumn('medecin.hopital.nom')->setVisible(false);

        return $grid->getGridResponse('GestionBundle:Infirmier:historique.html.twig');
    }

    /**
     * Creates a form to delete a medecin entity.
     *
     * @param Infirmier $infirmier The medecin entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Infirmier $infirmier)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_gestion_infirmier_delete', ['id' => $infirmier->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @return mixed
     */
    private function createPatientForm()
    {
        $form = $this->createForm(new SearchType(), [
            'action' => $this->generateUrl('admin_gestion_infirmier_patient'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit', ['label' => 'Rechercher']);

        return $form;
    }
}
