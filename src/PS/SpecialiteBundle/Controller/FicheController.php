<?php

namespace PS\SpecialiteBundle\Controller;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Form\SearchType;
use PS\SpecialiteBundle\Entity\Champ;
use PS\SpecialiteBundle\Entity\DonneeChamp;
use PS\SpecialiteBundle\Entity\Etape;
use PS\SpecialiteBundle\Entity\Fiche;
use PS\SpecialiteBundle\Entity\historiqueFiche;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FicheController extends Controller
{
    /**
     * @param Patient $patient
     * @return mixed
     */
    public function indexAction(Request $request, Patient $patient)
    {
        if ($patient->getPersonne()->getHopital() != $this->getUser()->getHopital()){
            $this->addFlash('warning', 'Impossible d\'accéder aux fiches de ce patient');
            return $this->redirectToRoute('admin_specialite_fiche_search');
        }

        $source = new Entity(Fiche::class);

        $source->manipulateQuery(function ($qb) use ($patient) {
            return $qb->andWhere('_a.patient = :patient')->setParameter('patient', $patient->getId());
        });

        $grid = $this->get('grid');

        $grid->setRouteUrl($this->generateUrl('admin_specialite_fiche_index', ['patient' => $patient->getId()]));

        $rowAction = new RowAction('Modifier', 'admin_specialite_fiche_edit');

        /*$rowAction->manipulateRender(function ($action, $row) {
        $action->setAttributes(['class' => 'btn btn-success btn-sm']);
        $action->setTitle('<i class="fa fa-edit"></i>');
        $action->setRouteParameters(['fiche' => $row->getField('id')]);

        return $action;
        });*/
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'PSSpecialiteBundle:Fiche:edit', 'parameters' => ['fiche' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('historique', 'admin_specialite_fiche_historique');

        $rowAction->manipulateRender(function ($action, $row) use ($patient) {
            $action->setAttributes(['class' => 'btn btn-success btn-sm']);
            $action->setTitle('<i class="fa fa-calendar"></i>');
            $action->setRouteParameters(['patient' => $patient->getId(), 'fiche' => $row->getField('id')]);
            return $action;
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Détails', 'admin_specialite_fiche_donnee');

        $rowAction->manipulateRender(function ($action, $row) {
            $action->setAttributes(['class' => 'btn btn-success btn-sm']);
            $action->setTitle('<i class="fa fa-plus"></i>');
            $action->setRouteParameters(['fiche' => $row->getField('id')]);
            return $action;
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Détails', 'admin_specialite_fiche_details');

        $rowAction->manipulateRender(function ($action, $row) {
            $action->setAttributes(['class' => 'btn btn-success btn-sm']);
            $action->setTitle('<i class="fa fa-th-list"></i>');
            $action->setRouteParameters(['fiche' => $row->getField('id')]);
            return $action;
        });

        $grid->addRowAction($rowAction);

        $grid->setSource($source);

        return $grid->getGridResponse('PSSpecialiteBundle:Fiche:index.html.twig', [
            'patient' => $patient,
        ]);
    }

    /**
     * @return mixed
     */
    public function searchAction(Request $request)
    {
        $form = $this->createPatientForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $identifiant = $form->get('identifiant')->getData();

            $pin = $form->get('pin')->getData();

            $count = $em->getRepository(Patient::class)->findByPass($identifiant, $pin);

            //var_dump($count[0][1]);die();
            if ($count[0][1] > 0) {
                $patient = $em->getRepository(Patient::class)->findByParam($identifiant, $pin);

                $patient = $patient[0];

                if (!$patient || ($patient->getPersonne()->getHopital() != $this->getUser()->getHopital())) {
                    $this->addFlash(
                        'patient',
                        'Ce patient n\'existe pas dans la base de données ou n\'est pas dans ce programme!'
                    );
                } else {
                    return $this->redirectToRoute('admin_specialite_fiche_index', ['patient' => $patient->getId()]);
                }
            } else {
                $this->addFlash(
                    'patient',
                    'Ce patient n\'existe pas dans la base de données!'
                );
            }
        }

        return $this->render('PSSpecialiteBundle:Fiche:search.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Patient $patient
     * @return mixed
     */
    public function newAction(Request $request, Patient $patient)
    {
        if ($patient->getPersonne()->getHopital() != $this->getUser()->getHopital()){
            $this->addFlash('warning', 'Impossible d\'accéder aux fiches de ce patient');
            return $this->redirectToRoute('admin_specialite_fiche_search');
        }
        $fiche      = new Fiche();
        $historique = new HistoriqueFiche();
        $form       = $this->createForm($this->get('app.fiche_type'), $fiche, ['method' => 'POST', 'action' => $this->generateUrl('admin_specialite_fiche_new', ['patient' => $patient->getId()]),
        ]);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $em   = $this->getDoctrine()->getManager();
            $date = new \DateTime();
            $fiche->setDateFiche($date);
            $fiche->setNumFiche($this->numero());
            $fiche->setPatient($patient);
            $historique->setDateHistoriqueFiche($date);
            $historique->setLibHistoriqueFiche('Création de la fiche patient');
            $historique->setUtilisateur($this->getUser());
            $fiche->addHistorique($historique);

            $em->persist($fiche);
            $em->flush();

            return $this->redirectToRoute('admin_specialite_fiche_donnee', [
                'specialite' => $fiche->getSpecialite()->getId(),
                'fiche'      => $fiche->getId(),
            ]);
        }

        return $this->render('PSSpecialiteBundle:Fiche:new.html.twig', [
            'patient' => $patient,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * @return mixed
     */
    private function numero()
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQueryBuilder();
        $query->select("MAX(a.numFiche) + 1 as ref")
            ->from("PSSpecialiteBundle:Fiche", 'a');

        return $query->getQuery()->getSingleScalarResult() ?: 1;

    }

    /**
     * @param Request $request
     * @param Fiche $fiche
     * @param Etape $etape
     * @return mixed
     */
    public function donneeAction(Request $request, Fiche $fiche, Etape $etape = null)
    {
        $specialite = $fiche->getSpecialite();
        $champs     = $specialite->getChamps();
        $em         = $this->getDoctrine()->getEntityManager();

        $champs = $champs->filter(function ($champ) use ($etape) {
            //dump($champ->getTypeChamp()->getAliasTypeChamp());
            return is_null($champ->getChampParent()) && (is_null($etape) || $champ->getEtape() == $etape);
        });

        $repChamp  = $em->getRepository(Champ::class);
        $repEtape  = $em->getRepository(Etape::class);
        $repDonnee = $em->getRepository(DonneeChamp::class);

        $etapes = $repEtape->findBy(['etapeParente' => null]);

        $donnees = $repDonnee->findByFicheEtape($fiche, $etape);

        $normalizeData = [];

        foreach ($donnees as $donnee) {
            $valeurChamp = @json_decode($donnee['valeurChamp']);
            if ($valeurChamp === null) {
                $valeurChamp = $donnee['valeurChamp'];
            }
            $normalizeData[] = ['name' => $donnee['aliasChamp'], 'value' => $valeurChamp];
        }

        $this->get('app.form_builder')->load($champs, []);

        $formBuilder = $this->get('app.form_builder')->getForm($champs);

        $formBuilder->add('save', SubmitType::class, ['label' => 'Valider', 'attr' => ['class' => 'ajax-submit']]);

        $form = $formBuilder->getForm();

        //Pré-persister les données
        foreach ($normalizeData as $value) {
            $form->get($value['name'])->setData($value['value']);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $redirect = $this->generateUrl('admin_specialite_fiche_donnee', ['fiche' => $fiche->getId(), 'etape' => $etape->getId()]) . '#form_fiche';

            if ($form->isValid()) {

                $data = $form->getData();

                foreach ($donnees as $donnee) {
                    $entity = $repDonnee->findOneByChamp($donnee['id']);
                    $em->remove($entity);
                    $em->flush();
                }

                foreach ($data as $name => $value) {
                    if (is_array($value)) {
                        $value = json_encode($value);
                    }

                    if ($value) {
                        $donnee     = new DonneeChamp();
                        $champ      = $repChamp->findOneByAliasChamp($name);
                        $chamParent = $champ->getChampParent();

                        $donnee->setValeurChamp($value);
                        $donnee->setFiche($fiche);
                        $donnee->setChamp($champ);
                        $em->persist($donnee);
                        $em->flush();

                        /*if (!$ChampParent || (
                    $chamParent && (
                    $champ->getValeurChampParent()
                    && $form->get($champParent->getAliasChamp())->getData() == $champ->getValeurChamp()
                    ))) {
                    $donnee->setValeurChamp($value);
                    $donnee->setFiche($fiche);
                    $donnee->setChamp($champ);
                    $em->persist($donnee);
                    $em->flush();

                    }*/

                    }

                }

                $historique = new HistoriqueFiche();
                $historique->setFiche($fiche);
                $historique->setEtape($etape);
                $historique->setUtilisateur($this->getUser());
                $historique->setLibHistoriqueFiche($normalizeData ? 'Modification' : 'Création');
                $fiche->addHistorique($historique);
                $em->flush();

                $message = 'Opération effectuée avec succès';
                $statut  = 1;
                $this->addFlash('success', $message);

            } else {
                $message = $this->get('app.form_error')->all($form);
                $statut  = 0;
                $this->addFlash('warning', $message);
            }

            if ($request->isXmlHttpRequest()) {
                $response = compact('statut', 'message', 'redirect');
                return new JsonResponse($response);
            } else {
                return $this->redirect($redirect);
            }

        }

        return $this->render('PSSpecialiteBundle:Fiche:donnees.html.twig', [

            'etapes'     => $etapes,
            'etape'      => $etape,
            'specialite' => $specialite,
            'fiche'      => $fiche,
            //'champ' => $champ,
            'form'       => $form->createView(),
            //'form2' => $__tp_f
        ]);

    }

    /**
     * @param Request $request
     * @param Fiche $fiche
     * @return mixed
     */
    public function detailsAction(Request $request, Fiche $fiche, Etape $etape = null)
    {
        $em       = $this->getDoctrine()->getManager();
        $repFiche = $em->getRepository(Fiche::class);

        $specialite = $fiche->getSpecialite();

        //$details = $repFiche->details($fiche);

        $champs = $specialite->getChamps();

        $champs = $champs->filter(function ($champ) use ($etape) {
            //dump($champ->getTypeChamp()->getAliasTypeChamp());
            return is_null($champ->getChampParent()) && (is_null($etape) || $champ->getEtape() == $etape);
        });

        $repChamp  = $em->getRepository(Champ::class);
        $repEtape  = $em->getRepository(Etape::class);
        $repDonnee = $em->getRepository(DonneeChamp::class);

        $etapes = $repEtape->findAll();

        $donnees = $repDonnee->findByFicheEtape($fiche, $etape);

        $normalizeData = [];

        foreach ($donnees as $donnee) {
            $valeurChamp = @json_decode($donnee['valeurChamp']);
            if ($valeurChamp === null) {
                $valeurChamp = $donnee['valeurChamp'];
            }
            $normalizeData[] = ['name' => $donnee['aliasChamp'], 'value' => $valeurChamp];
        }

        $this->get('app.form_builder')->load($champs, []);

        $formBuilder = $this->get('app.form_builder')->getForm($champs);

        $form = $formBuilder->getForm();

        foreach ($normalizeData as $value) {
            $form->get($value['name'])->setData($value['value']);
        }

        //$formBuilder = $this->get('app.form_builder')->getForm($collectChamps);

        return $this->render('PSSpecialiteBundle:Fiche:details.html.twig', [
            'form'       => $form->createView(),
            'etapes'     => $etapes,
            'etape'      => $etape,
            'fiche'      => $fiche,
            'specialite' => $specialite,
        ]);
    }

    /**
     * @param Request $request
     * @param Specialite $specialite
     * @return mixed
     */
    public function etapeAction(Request $request, Specialite $specialite)
    {
        $em = $this->getDoctrine()->getManager();

        $etapes = $repEtape->findBy(['etapeParente' => null, 'specialite' => $specialite]);

        $specialites = $this->get('knp_paginator')->paginate(
            $em->getRepository(Specialite::class)->createQueryBuilder('u'),
            $request->query->get('page', 1) /*page number*/,
            20/*limit per page*/
        );

        return $this->render('PSSpecialiteBundle:Fiche:etape.html.twig', [
            'specialites' => $specialites,
        ]);
    }

    /**
     * @param Request $request
     * @param Fiche $fiche
     * @return mixed
     */
    public function editAction(Request $request, Fiche $fiche)
    {
        $historique = new historiqueFiche();
        $form       = $this->createForm($this->get('app.fiche_type'), $fiche, [
            'method' => 'POST'
            , 'action' => $this->generateUrl('admin_specialite_fiche_edit', ['fiche' => $fiche->getId()]),
        ]);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();

            $historique->setDateHistoriqueFiche($date);
            $historique->setLibHistoriqueFiche('Modification de la fiche patient');
            $historique->setUtilisateur($this->getUser());

            $fiche->addHistorique($historique);

            //$em->persist($fiche);
            $em->flush();
        }

        //$form = $this->creat
        return $this->render('PSSpecialiteBundle:Fiche:edit.html.twig', [
            'fiche' => $fiche,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param Patient $patient
     * @param Fiche $fiche
     * @return mixed
     */
    public function historiqueAction(Request $request, Patient $patient, Fiche $fiche)
    {

        $historiques = $this->get('knp_paginator')->paginate(
            $fiche->getHistoriques(),
            $request->query->get('page', 1) /*page number*/,
            20/*limit per page*/
        );

        return $this->render('PSSpecialiteBundle:Fiche:historique.html.twig', [
            'historiques' => $historiques,
            'fiche'       => $fiche,
        ]);
    }

    /**
     * @return mixed
     */
    private function createPatientForm()
    {
        $form = $this->createForm(SearchType::class, null, [
            'action' => $this->generateUrl('admin_specialite_fiche_search'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit', ['label' => 'Rechercher']);

        return $form;
    }

}
