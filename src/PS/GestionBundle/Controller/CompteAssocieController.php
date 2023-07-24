<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Service\RowAction;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Entity\Consultation;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Form\AssociationType;
use PS\GestionBundle\Form\AssociePasswordType;
use PS\UtilisateurBundle\Entity\CompteAssocie;
use PS\UtilisateurBundle\Entity\Personne;
use PS\UtilisateurBundle\Entity\Utilisateur;
use PS\UtilisateurBundle\Form\UtilisateurType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class CompteAssocieController extends Controller
{
    /**
     * @return mixed
     */
    public function indexAction()
    {



        // ('countryCode' => 'countryName')
        // => ['AF' => 'Afghanistan', 'AX' => 'Åland Islands', ...]

       
        $em         = $this->getDoctrine()->getManager();
        $repPatient = $em->getRepository(Patient::class);
        $user       = $this->getUser();
        $source     = new Entity(CompteAssocie::class);

        $grid = $this->get('grid');

        $grid->setRouteUrl($this->generateUrl('gestion_admin_patient_associe_index'));
     
        $source->manipulateQuery(function ($qb) use ($user) {
            $qb->andWhere("_a.patient = :patient");

            $qb->setParameter('patient', $user->getPersonne()->getPatient());

        });

        //$source->initQueryBuilder($em->getRepository(Personne::class)->associes($personne));

        $grid->setSource($source);

        $rowAction = new RowAction('Modifier', 'admin_gestion_patient_modifier');
         $rowAction->setAttributes(['ajax' => false]);
        $rowAction->manipulateRender(function ($action, $row) {

            $action->setRouteParameters(['id' => $row->getField('associe.id')]);

            return $action;
        });

        $grid->addRowAction($rowAction);

        /*$rowAction = new RowAction('Compte', 'gestion_admin_patient_associe_cpte_edit');
        $rowAction->manipulateRender(function ($action, $row) use ($repPatient) {
        $action->setRouteParameters(['id' => $row->getField('id')]);
        $action->setAttributes(['class' => 'btn btn-info btn-sm']);
        $action->setTitle('<i class="fa fa-edit"></i>');
        return $action;
        });
        $grid->addRowAction($rowAction);*/

        $rowAction = new RowAction('historique', 'gestion_admin_patient_associe_cpte_historique');
        $rowAction->setAttributes(['ajax' => false]);
        $rowAction->manipulateRender(function ($action, $row) use ($repPatient) {
            $action->setRouteParameters(['id' => $row->getField('associe.id')]);
            $action->setAttributes(['class' => 'btn btn-default btn-sm']);
           
            return $action;
        });
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('RDV', 'admin_gestion_rdv_index');
        //$rowAction->setAttributes([]);
        $rowAction->manipulateRender(function ($action, $row) use ($repPatient) {
            $action->setRouteParameters(['patient' => $row->getField('associe.id')]);
            $action->setAttributes(['icon' => 'fa fa-calendar', 'ajax' => false]);
            //$action->setTitle('<i class="fa fa-calendar"></i>');

            return $action;
        });
        $grid->addRowAction($rowAction);

        //$grid->getColumn('roles')->setVisible(false);*/

        return $grid->getGridResponse('GestionBundle:Compte:index.html.twig', ['patient' => $user->getPersonne()->getPatient()]);
    }

    /**
     * @return mixed
     */
    public function newAction(Request $request)
    {

        $user        = $this->getUser();
        $utilisateur = new Utilisateur();
        $form        = $this->createForm(UtilisateurType::class, $utilisateur, [
            'action' => $this->generateUrl('gestion_admin_patient_associe_cpte_new'),
            'method' => 'POST',
        ]);

        $form->remove('email');
        $form->remove('hopital');
        $form->remove('roles');
        $form->remove('enabled');
        $form->remove('locked');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $personne = new Personne();
            $personne->setContact($user->getPersonne()->getContact());
            $utilisateur->setPersonne($personne);
            $utilisateur->setParent($user);
            $utilisateur->setEnabled(true);
            $utilisateur->setEmail($user->getEmail());
            $utilisateur->setRoles(['ROLE_CUSTOMER']);

            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($utilisateur);

            $patient = new Patient();
            $patient->setPersonne($utilisateur->getPersonne());

            $em = $this->getDoctrine()->getManager();
            $em->persist($patient);
            $em->flush();

            return $this->redirectToRoute('admin_gestion_patient_modifier', ['id' => $patient->getId()]);
        }

        return $this->render('GestionBundle:Compte:new.html.twig', ['form' => $form->createView(), 'utilisateur' => $utilisateur]);
    }

    /**
     * @param Utilisateur $Utilisateur
     * @return mixed
     */
    public function editAction(Utilisateur $utilisateur)
    {
        $user = $this->getUser();
        $em   = $this->getDoctrine()->getManager();
        $form = $this->createForm('PS\UtilisateurBundle\Form\UtilisateurType', $utilisateur, [
            'action'           => $this->generateUrl('gestion_admin_patient_associe_cpte_edit', ['id' => $utilisateur->getId()]),
            'method'           => 'POST',
            'passwordRequired' => false,
        ]);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur->getPersonne()->setContact($user->getPersonne()->getContact());
            $em->flush();

            return $this->redirectToRoute('gestion_admin_patient_associe_index');
        }

        return $this->render('GestionBundle:Compte:edit.html.twig', ['form' => $form->createView(), 'utilisateur' => $utilisateur]);
    }

    /**
     * @param Utilisateur $Utilisateur
     * @param Patient $patient
     * @return mixed
     */
    public function infosAction(Utilisateur $utilisateur, Patient $patient)
    {
        return $this->render('patient/info.html.twig', [
            'user'    => $user,
            'patient' => $patient,
        ]);
    }

    /**
     * @param Utilisateur $Utilisateur
     * @param Patient $patient
     * @return mixed
     */
    public function historiqueAction(Request $request, Patient $patient)
    {
        $this->denyAccessUnlessGranted('ROLE_EDIT_PATIENT', $patient);
        $em         = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Consultation::class);
        $source     = new Entity('GestionBundle:Consultation');

       
        $statut = $request->get('statut');

        $grid = $this->get('grid');

        $source->manipulateQuery(function ($qb) use ($patient, $statut) {

            $qb->andWhere("_a.patient = :patient");
            $qb->setParameter('patient', $patient);

            if ($statut) {
                $qb->andWhere('_a.statut = :statut');
                $qb->setParameter('statut', $statut);
            }

            $qb->orderBy('_a.dateConsultation', 'DESC');

        });

        $grid->setSource($source);

        $rowAction = new RowAction('Détails', 'admin_consultation_historique_show');
        $rowAction->manipulateRender(function ($action, $row) {

            $action->setRouteParameters([
                'id1' => $row->getField('id')
                , 'id' => $row->getField('patient.id'),
            ]);
            $action->setAttributes(['class' => 'btn btn-default btn-sm']);
            $action->setTitle('<i class="fa fa-list-alt"></i>');

            return $action;
        });
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Imprimer', 'admin_consultation_preview_print');
        $rowAction->manipulateRender(function ($action, $row) {
            if ($row->getField('statut') != -1) {
                $action->setRouteParameters([
                    'id1' => $row->getField('id')
                    , 'id' => $row->getField('patient.id'),
                ]);
                $action->setAttributes(['class' => 'btn btn-success btn-sm']);
                $action->setTitle('<i class="fa fa-print"></i>');

                return $action;
            }

        });
        $grid->addRowAction($rowAction);

        $grid->getColumn('statut')->manipulateRenderCell(function ($value) {
            if ($value == -1) {
                return '<span class="label label-default">En attente du medecin</span>';
            }

            return '<span class="label label-success">Validé</span>';
        });

        return $grid->getGridResponse('GestionBundle:Compte:historique.html.twig', compact('role'));
    }

    /**
     * @param Consultation $consultation
     * @param Utilisateur $Utilisateur
     * @param Patient $patient
     * @return mixed
     */
    public function consultationAction(Utilisateur $Utilisateur, Consultation $consultation)
    {
        return $this->render('GestionBundle:Compte:consultation.html.twig');
    }

    /**
     * @param Request $request
     * @param Personne $personne
     */
    public function accessAction(Request $request, Personne $personne)
    {

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function associationAction(Request $request)
    {
        $em         = $this->getDoctrine()->getManager();
        $form       = $this->createForm(AssociationType::class);
        $repPatient = $em->getRepository(Patient::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data        = $form->getData();
            $identifiant = explode('/', $data['identifiant']);

            if ($patient = $repPatient->findByPinPass($identifiant[0], $identifiant[1])) {
                $patient = $patient[0];
                foreach ($data['identifiants'] as $_identifiant) {
                    $_identifiant = explode('/', $_identifiant);
                    $_patient     = $repPatient->findByPinPass($_identifiant[0], $_identifiant[1]);
                    if ($_patient) {
                        $_patient = $_patient[0];

                        $_utilisateur      = $_patient->getPersonne()->getUtilisateur();
                        $utilisateurParent = $patient->getPersonne()->getUtilisateur();

                        $_utilisateur->setParent($utilisateurParent);
                        $_utilisateur->setEmail($utilisateurParent->getEmail());

                    }
                }

                $em->flush();

            }
            /*$associes = $em->getRepository(Utilisateur::class)->associes($user, true);

            $userManager = $this->container->get('fos_user.user_manager');
            foreach ($associes as $associe) {
            $user = $userManager->findUserByUsername($associe->getUsername());
            $user->setPlainPassword($data['plainPassword']);
            $userManager->updatePassword($user);
            $userManager->updateUser($user, true);
            }*/

            return $this->redirectToRoute('gestion_admin_patient_associe_index');
        }

        return $this->render('GestionBundle:Compte:association.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function passwordAction(Request $request)
    {
        $user = $this->getUser();
        $em   = $this->getDoctrine()->getManager();
        $form = $this->createForm(AssociePasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $associes = $em->getRepository(Utilisateur::class)->associes($user, true);

            $userManager = $this->container->get('fos_user.user_manager');
            foreach ($associes as $associe) {
                $user = $userManager->findUserByUsername($associe->getUsername());
                $user->setPlainPassword($data['plainPassword']);
                $userManager->updatePassword($user);
                $userManager->updateUser($user, true);
            }

            return $this->redirectToRoute('gestion_admin_patient_associe_index');
        }

        return $this->render('GestionBundle:Compte:password.html.twig', ['form' => $form->createView()]);
    }

}
