<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Service\RowAction;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Entity\Consultation;
use PS\GestionBundle\Entity\DisponibiliteMedicament;
use PS\GestionBundle\Entity\GardePharmacie;
use PS\GestionBundle\Entity\Historique;
use PS\GestionBundle\Entity\InfoPharmacie;
use PS\GestionBundle\Entity\MedicamentPharmacie;
use PS\GestionBundle\Entity\OperationPharmacie;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Entity\Pharmacie;
use PS\GestionBundle\Form\ExportType;
use PS\GestionBundle\Form\PharmacieType;
use PS\GestionBundle\Form\SearchType;
use PS\ParametreBundle\Entity\Commune;
use PS\ParametreBundle\Entity\Medicament;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SearchType as FormSearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Consultation controller.
 *
 */
class PharmacieController extends Controller
{

    /**
     * @return mixed
     */
    private function getPharmacie()
    {
        return $this->getUser()->getPharmacie();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $source = new Entity(Pharmacie::class);

        $grid = $this->get('grid');

        $grid->setSource($source);

        $rowAction = new RowAction('Détails', 'admin_pharmacie_show');

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_pharmacie_edit');

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'admin_pharmacie_delete');

        $grid->addRowAction($rowAction);

        return $grid->getGridResponse('pharmacie/grid.html.twig');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function newAction(Request $request)
    {
        $pharmacie = new Pharmacie();
        $form      = $this->createForm(PharmacieType::class, $pharmacie, [
            'action' => $this->generateUrl('admin_pharmacie_add'),
        ]);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pharmacie);
            $em->flush();
            $this->addFlash('success', 'Pharmacie mise à jour avec succès');

            return $this->redirectToRoute('admin_pharmacie_show', ['id' => $pharmacie->getId()]);
        }

        return $this->render('pharmacie/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function exportAction(Request $request)
    {
        //$fichier = new Fichier();
        $form = $this->createForm(ExportType::class);
        $form->handleRequest($request);

        $em     = $this->getDoctrine()->getManager();
        $errors = [];

        /**
         * @todo: Déplacer dans un service
         */
        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $fichier = $this->get('app.psm_logo_uploader')->upload($form->get('file')->getData(), null, $path, 'liste_pharmacie');
                /*$em->persist($fichier);
                $em->flush();*/

                $repCommune   = $em->getRepository(Commune::class);
                $repPharmacie = $em->getRepository(Pharmacie::class);

                $spreadsheet = $this->get('phpoffice.spreadsheet');
                $reader      = $spreadsheet->createReader('Xlsx');
                $loader      = $reader->load($path);

                $count = $loader->getSheetCount();
                $total = 0;

                //echo $count;

                for ($i = 0; $i < $count; $i++) {

                    $loader->setActiveSheetIndex($i);
                    $data = $loader->getActiveSheet()->toArray();

                    $_pharmacies = [];
                    foreach ($data as $_row) {
                        //$this->get('file_export_validator')->validateByType($_row, '');
                        if (!empty($_row[0])) {
                            $_row[0] = trim($_row[0]);
                            if (strtolower($_row[0]) != 'officine' && (!empty($_row[3]) || !empty($_row[4]))) {
                                if (stripos($_row[0], 'pharmacie') === 0) {
                                    $_pharmacies[] = [
                                        'libPharmacie'          => $_row[0],
                                        'nomResponsable'        => trim($_row[1]),
                                        'contacts'              => $_row[2],
                                        'ville'                 => trim($_row[3]),
                                        'commune'               => trim($_row[4]), //5
                                        'localisationPharmacie' => (string) $_row[5], //6
                                        //'garde' => $_row[]
                                    ];
                                }
                            }
                        }
                    }

                    $batchSize = 50;

                    foreach ($_pharmacies as $_pharmacie) {

                        $_commune = $_pharmacie['commune'];
                        $_ville   = $_pharmacie['ville'];

                        if ($_commune && !$commune = $repCommune->findOneByLibCommune($_commune)) {
                            $commune = new Commune();
                            $commune->setLibCommune($_commune);
                            $em->persist($commune);
                            //$em->flush();
                        }

                        if ($_ville && !$ville = $repVille->findOneByNom($_ville)) {
                            $ville = new Commune();
                            $ville->setNom($_ville);
                            $em->persist($ville);

                            //$em->flush();
                        }

                        if (!$repPharmacie->existsByCommune($_pharmacie['libPharmacie'], $commune, $ville)) {
                            $pharmacie = new Pharmacie();
                            $info      = new InfoPharmacie();

                            $info->setNomResponsable($_pharmacie['nomResponsable']);
                            $info->setContacts($_pharmacie['contacts']);
                            $info->setLocalisationPharmacie($_pharmacie['localisationPharmacie']);

                            $pharmacie->setLibPharmacie($_pharmacie['libPharmacie']);
                            $info->setCommune($commune);
                            $info->setVille($ville);
                            $info->setPharmacie($pharmacie);
                            $em->persist($info);
                            $em->persist($pharmacie);

                            $em->flush();

                            $total += 1;
                        }
                    }
                }

                if (count($_pharmacies)) {
                    $this->addFlash('message', "{$total} Pharmacie(s) ajoutée(s) ou modifiée(s)");
                } else {
                    $this->addFlash('error', 'Fichier vide');
                }

                return $this->redirectToRoute('admin_pharmacie_export');
            } else {
                $errors = $this->get('app.form_error')->all($form);
            }
        }

        return $this->render('pharmacie/export.html.twig', [
            'form'   => $form->createView(),
            'errors' => $errors,
        ]);
    }

    /**
     * @param Request $request
     * @param Pharmacie $pharmacie
     * @return mixed
     */
    public function editAction(Request $request, Pharmacie $pharmacie)
    {

        $form = $this->createForm(PharmacieType::class, $pharmacie, [
            'action' => $this->generateUrl('admin_pharmacie_edit', ['id' => $pharmacie->getId()]),
        ]);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Pharmacie mise à jour avec succès');

            return $this->redirectToRoute('admin_pharmacie_show', ['id' => $pharmacie->getId()]);
        }

        return $this->render('pharmacie/edit.html.twig', ['pharmacie' => $pharmacie, 'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param Pharmacie $pharmacie
     * @return mixed
     */
    public function showAction(Request $request, Pharmacie $pharmacie)
    {
        $form = $this->createForm(PharmacieType::class, $pharmacie, [
            'action' => $this->generateUrl('admin_pharmacie_edit', ['id' => $pharmacie->getId()]),
        ]);

        return $this->render('pharmacie/show.html.twig', ['pharmacie' => $pharmacie, 'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param Pharmacie $pharmacie
     * @return mixed
     */
    public function deleteAction(Request $request, Pharmacie $pharmacie)
    {
        return $this->render('pharmacie/delete.html.twig');
    }

    /**
     * @param Request $request
     */
    public function gardePharmacieAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $mode         = $request->query->get('mode');
        $repGarde     = $em->getRepository(GardePharmacie::class);
        $repPharmacie = $em->getRepository(Pharmacie::class);
        $user         = $this->getUser();
        $canAdd       = !$user->hasRole('ROLE_CUSTOMER');
        if ($mode == 'new' && $canAdd) {

            $form = $this->createForm(ExportType::class, null, [
                'action' => $this->generateUrl('admin_pharmacie_garde', ['mode' => 'new']),
            ]);
            $form->handleRequest($request);

            $errors = [];

            if ($form->isSubmitted()) {
                if ($form->isValid()) {

                    $fichier = $this->get('app.psm_logo_uploader')->upload($form->get('file')->getData(), null, $path, 'pharmacie_garde');
                    /*$em->persist($fichier);
                    $em->flush();*/

                    $repCommune   = $em->getRepository(Commune::class);
                    $repPharmacie = $em->getRepository(Pharmacie::class);

                    $spreadsheet = $this->get('phpoffice.spreadsheet');
                    $reader      = $spreadsheet->createReader('Xlsx');
                    $loader      = $reader->load($path);

                    $notExportables = [];

                    $count = $loader->getSheetCount();
                    $total = 0;

                    //echo $count;

                    for ($i = 0; $i < $count; $i++) {

                        $loader->setActiveSheetIndex($i);
                        $data = $loader->getActiveSheet()->toArray();

                        $_pharmacies = [];
                        foreach ($data as $_row) {
                            $_row[0] = trim($_row[0]);
                            if ($_row[0] && strtolower($_row[0]) != 'officine') {
                                $_pharmacies[] = [
                                    'libPharmacie' => $_row[0],
                                    'periode'      => $_row[1],
                                ];
                            }
                        }

                        foreach ($_pharmacies as $_pharmacie) {

                            if ($pharmacie = $repPharmacie->findOneByLibPharmacie($_pharmacie['libPharmacie'])) {

                                list($dateDebut, $dateFin) = explode('-', $_pharmacie['periode']);
                                $dateDebut                 = new \DateTime(str_replace('/', '-', $dateDebut));
                                $dateFin                   = new \DateTime(str_replace('/', '-', $dateFin));
                                $currentDate               = new \DateTime();
                                $flush                     = false;

                                if ($dateDebut > $dateFin) {
                                    $tmp       = $dateDebut;
                                    $dateDebut = $dateFin;
                                    $dateFin   = $tmp;
                                }

                                if (!$garde = $repGarde->findOneByPharmacie($pharmacie->getId())) {
                                    $garde = new GardePharmacie();
                                    $garde->setPharmacie($pharmacie);
                                }

                                if ($dateDebut <= $currentDate && $dateFin >= $currentDate) {
                                    $garde->setDateDebut($dateDebut);
                                    $garde->setDateFin($dateFin);
                                    $em->persist($garde);

                                    $total += 1;
                                    $flush = true;
                                } else {
                                    if ($garde->getId()) {
                                        $em->remove($garde);
                                        $flush = true;
                                    }
                                }

                                if ($flush) {
                                    $em->flush();
                                }
                            } else {
                                $notExportables[] = $_pharmacie['libPharmacie'];
                            }
                        }
                    }

                    if ($total > 0) {
                        $message = "{$total} pharmacie(s) exportée(s) ou modifiée(s)";
                        $this->addFlash('message', $message);
                    } else {
                        $this->addFlash('error', 'Fichier vide');
                    }

                    return $this->redirectToRoute('admin_pharmacie_garde', ['mode' => 'view']);
                } else {
                    $errors = $this->get('app.form_error')->all($form);
                }
            }

            return $this->render('pharmacie/garde.html.twig', ['form' => $form->createView(), 'errors' => $errors, 'mode' => $mode, 'canAdd' => $canAdd]);
        }

        //$data = $repPharmacie->gardes(true, !$user->hasRole('ROLE_CUSTOMER'));

        $source = /*new Vector($data);*/ new Entity(GardePharmacie::class);

        $source->manipulateQuery(function ($qb) {
            $qb->andWhere('CURRENT_DATE() BETWEEN _a.dateDebut AND _a.dateFin');
        });

        $grid = $this->get('grid');

        $grid->setSource($source);

        return $grid->getGridResponse('pharmacie/garde.html.twig', ['mode' => $mode, 'canAdd' => $canAdd]);
    }

    /**
     * @Security("is_granted('ROLE_PHARMACIE')")
     */
    public function searchAction(Request $request)
    {
        $session = $request->getSession();
        //$session->remove('patient');
        $form = $this->createForm(SearchType::class, null, [
            'action'             => $this->generateUrl('admin_pharmacie_search'),
            'method'             => 'POST',
            'with_reference'     => true,
            'required_reference' => false,
        ]);

        $form->add('submit', SubmitType::class, ['label' => 'Rechercher']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $identifiant = $form->get('identifiant')->getData();
            $reference   = $form->get('reference')->getData();
            $pin         = $form->get('pin')->getData();

            $patient = $em->getRepository(Patient::class)->findOneBy(compact('identifiant', 'pin'));

            //19617726

            if (!$patient) {
                $this->addFlash(
                    'patient',
                    'Ce patient n\'existe pas dans la base de données!'
                );
            } else {
                $message = "Accès à votre profil par le Pharmacien %s . Pharmacie (%s)\nPASS SANTE(https://santemousso.net)";

                $user     = $this->getUser();
                $personne = $user->getPersonne();

                $pharmacie = $user->getPharmacie();

                $nom = $user->getUsername();

                if ($personne->getNomComplet()) {
                    $nom .= '(' . $personne->getNomComplet() . ')';
                }

                $message = sprintf($message, $nom, $pharmacie->getLibPharmacie());

                if ($contact = $patient->getPersonne()->getSmsContact()) {

                    $smsManager = $this->get('app.ps_sms');

                    $smsManager->send($message, $contact);
                }

                $this->get('app.action_logger')->add($message, $patient, true, Historique::PROFILE_VIEW);

                if ($reference) {
                    $consultation = $em->getRepository(Consultation::class)->findOneBy(['refConsultation' => $reference]);

                    if ($consultation && ($consultation->getPatient() == $patient) && $consultation->getMedicaments()->count()) {
                        return $this->redirectToRoute('admin_pharmacie_medicament', ['id' => $consultation->getId(), 'patient' => $patient->getId()]);
                    }

                    $this->addFlash(
                        'patient',
                        'Référence inexistante ou n\'appartenant pas au patient ou ne contient pas de prescriptions'
                    );

                    return $this->redirectToRoute('admin_pharmacie_search');
                }
                //$smsManager->send(sprintf($message, $nom, $label), $contact);

                return $this->redirectToRoute('admin_pharmacie_ordonnance', ['id' => $patient->getId()]);
            }

            return $this->redirectToRoute('admin_pharmacie_search');
        }

        return $this->render('gestion/patient/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Patient $patient
     * @return mixed
     */
    public function ordonnanceAction(Request $request, Patient $patient = null)
    {

        $em       = $this->getDoctrine()->getManager();
        $personne = $this->getUser()->getPersonne()->getId();
        if (!$patient) {
            $patient = $em->getRepository(Patient::class)->findOneByPersonne($personne);
        }

        if (!$patient) {
            throw $this->createAccessDeniedException();
        }

        $consultations = $em->getRepository(Consultation::class)
            ->findOrdonnanceByPatient($patient->getId());

        return $this->render('pharmacie/ordonnance.html.twig', [
            'consultations' => $consultations,
            'patient'       => $patient,
        ]);
    }

    /**
     * @param Patient $patient
     * @param OperationPharmacie $operation
     * @return mixed
     */
    public function historiqueAction(Patient $patient, OperationPharmacie $operation)
    {
        $em = $this->getDoctrine()->getManager();

        $medicaments = $em->getRepository(MedicamentPharmacie::class)->findByOperation($operation);

        return $this->render('pharmacie/historique.html.twig', [
            'medicaments' => $medicaments,
            'patient'     => $patient,
            'operation'   => $operation,
        ]);
    }

    /**
     * @return mixed
     */
    private function getPatient()
    {
        return $this->getUser()->getPersonne()->getPatient();
    }
    /**

     * @param Request $request
     * @param Patient $patient
     * @param Consultation $consultation
     * @return mixed
     */
    public function medicamentAction(Request $request, Patient $patient, Consultation $consultation)
    {
        $paginator = $this->get('knp_paginator');
        $em        = $this->getDoctrine()->getManager();

        if (!$this->isGranted('ROLE_PHARMACIE') && ($this->getPatient() != $consultation->getPatient())) {

            throw $this->createAccessDeniedException();
        }

        $errors          = [];
        $operationsQuery = $em->getRepository(OperationPharmacie::class)->findByConsultation($consultation, ['dateOperation' => 'DESC']);
        $operations      = $paginator->paginate(
            $operationsQuery,
            $request->query->getInt('page', 1),
            5
        );

        $assurances = $patient->getLigneAssurances();

        $medicaments = $consultation->getMedicaments();
        $medicaments = $medicaments->filter(function ($medicament) {
            return $medicament->getLastOperation() === true || $medicament->getLastOperation()->getStatut() == 0;
        });

        $pharmacies = $em->getRepository(Pharmacie::class)->findAll();
        $pharmacie  = new Pharmacie();

        if ($request->isMethod('POST') && $this->isGranted('ROLE_PHARMACIE')) {
            $operation = new OperationPharmacie();

            $commentaires  = $request->request->get('commentaire');
            $substitutions = $request->request->get('substitution');
            $prix          = $request->request->get('prixMedicament');
            $montantVerse  = intval($request->request->get('montantVerse'));
            $montantRendu  = intval($request->request->get('montantRendu'));

            $statuts = $request->request->get('statut');
            $user    = $this->getUser();

            if (count($commentaires) !== count($medicaments)) {
                $errors[] = 'Formulaire invalide';
            }

            /*if ($statuts && (!$montantVerse && $montantRendu === '')) {
            $errors[] = 'Veuillez renseigner la monnaie rendue et le montant versé';
            }*/

            $countPrix = 0;

            foreach ($statuts as $index => $statut) {
                if (!empty($prix[$index])) {
                    $countPrix += 1;
                    break;
                }
            }

            if ($countPrix === 0) {
                $errors[] = 'Veuillez renseigner le prix des médicaments dont la case a été cochée';
            }

            if (!$errors) {

                $operation->setUser($user);
                $operation->setConsultation($consultation);
                $operation->setPharmacie($user->getPharmacie());
                $operation->setMontantVerse($montantVerse);
                $operation->setMontantRendu($montantRendu);

                foreach ($medicaments as $_medicament) {
                    foreach ($commentaires as $medicament => $commentaire) {
                        if ($_medicament->getId() == $medicament && ($commentaire || isset($statuts[$_medicament->getId()]))) {

                            $substitution = $substitutions[$_medicament->getId()];

                            if ($substitution) {

                                $_medicament->setSubstitution($substitution);

                                $em->persist($_medicament);

                                $em->flush();
                            }

                            $pharmacie = new MedicamentPharmacie();
                            $pharmacie->setMedicament($_medicament);
                            $pharmacie->setOperation($operation);
                            $pharmacie->setCommentaire($commentaire);
                            $pharmacie->setSubstitution($substitution);
                            $pharmacie->setStatut(isset($statuts[$_medicament->getId()]) ? 1 : 0);
                            $pharmacie->setPrixMedicament($prix[$_medicament->getId()]);
                            $em->persist($pharmacie);
                            $operation->addMedicamentPharmacy($pharmacie);
                        }
                    }
                }

                $em->persist($operation);
                $em->flush();

                return $this->redirectToRoute('admin_pharmacie_historique_med', ['patient' => $patient->getId(), 'id' => $operation->getId()]);
            }
        }

        return $this->render('pharmacie/medicament.html.twig', [
            'medicaments'  => $medicaments,
            'patient'      => $patient,
            'pharmacies'   => $pharmacies,
            'errors'       => $errors,
            'assurances'   => $assurances,
            //'form' => $form->createView(),

            'consultation' => $consultation,
            'operations'   => $operations,
        ]);
    }

    /**
     * @param Request $request
     */
    public function listeMedicamentsAction(Request $request)
    {
        $user   = $this->getUser();
        $source = new Entity(DisponibiliteMedicament::class);

        $grid = $this->get('grid');

        $grid->setSource($source);

        $source->manipulateQuery(function ($query) use ($user) {
            $query->andWhere('_a.pharmacie = :pharmacie');
            $query->setParameter('pharmacie', $user->getPharmacie()->getId());
        });

        $grid->getColumn('disponible')->manipulateRenderCell(function ($value) {
            if (!$value) {
                return '<span class="label label-default">Non</span>';
            }

            return '<span class="label label-success">Oui</span>';
        })->setSafe(false);

        return $grid->getGridResponse('pharmacie/liste_medicament.html.twig');
    }

    /**
     * @param Request $request
     * @param Consultation $consultation
     * @return mixed
     */
    public function verificationMedicamentAction(Request $request, Consultation $consultation = null)
    {
        $form = $this->createFormBuilder()
            ->add('keyword', FormSearchType::class)
            ->getForm();
        $medicaments     = [];
        $listMedicaments = [];
        if ($consultation) {
            foreach ($consultation->getMedicaments() as $medicament) {
                $medicaments[] = $medicament->getMedicament();
            }
        }

        $form->handleRequest($request);

        $keyword = $form->get('keyword')->getData() ?: implode(',', $medicaments);

        if ($form->isValid() && $form->isSubmitted()) {

            $paginator = $this->get('knp_paginator');
            $em        = $this->getDoctrine()->getManager();
            $query     = $em->getRepository(Pharmacie::class)
                ->searchMedicament(
                    $keyword,
                    null,
                    false
                );
            $listMedicaments = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                10
            );
        }

        return $this->render('pharmacie/verification_medicament.html.twig', [
            'form'            => $form->createView(),

            'listMedicaments' => $listMedicaments,
            'consultation'    => $consultation,
            'keyword'         => $keyword,
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function disponibiliteMedicamentsAction(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(ExportType::class);
        $form->handleRequest($request);

        $pharmacie = $user->getPharmacie();

        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $fichier = $this->get('app.psm_logo_uploader')->upload($form->get('file')->getData(), null, $path, 'liste_dispo_medicament');

            $repMedicament = $em->getRepository(Medicament::class);
            $repDispo      = $em->getRepository(DisponibiliteMedicament::class);

            $spreadsheet = $this->get('phpoffice.spreadsheet');
            $reader      = $spreadsheet->createReader('Xlsx');
            $loader      = $reader->load($path);

            $count = $loader->getSheetCount();

            //echo $count;

            for ($i = 0; $i < $count; $i++) {

                $loader->setActiveSheetIndex($i);
                $data = $loader->getActiveSheet()->toArray();

                $_medicaments = [];
                foreach ($data as $_row) {
                    if (!empty($_row[0])) {
                        $_row[0] = trim($_row[0]);
                        if ($_row[0] != 'Médicament') {
                            $_medicaments[] = [
                                'medicament' => $_row[0],
                                'prix'       => $_row[1],
                                'disponible' => trim($_row[2]),
                            ];
                        }
                    }
                }

                foreach ($_medicaments as $_medicament) {
                    if (!$medicament = $repMedicament->findOneByNom($_medicament['medicament'])) {
                        $medicament = new Medicament();
                        $medicament->setNom($_medicament['medicament']);
                        $em->persist($medicament);
                    }

                    $disponibilite = !in_array($_medicament['disponible'], [0, 'non', '', '0', false]) ? true : false;
                    $prix          = $_medicament['prix'];

                    if ($dispo = $repDispo->findOneBy(['medicament' => $medicament->getId(), 'pharmacie' => $pharmacie->getId()])) {
                        $dispo->setDisponible($disponibilite);
                        $dispo->setPrixMedicament($prix);
                    } else {

                        $dispo = new DisponibiliteMedicament();
                        $dispo->setMedicament($medicament);
                        $dispo->setPrixMedicament($prix);
                        $dispo->setPharmacie($pharmacie);
                        $dispo->setDisponible($disponibilite);
                    }

                    $em->persist($dispo);
                    $em->flush();
                }
            }

            if (count($_medicaments)) {
                $this->addFlash('message', 'Pharmacies ajoutées avec succès');
            } else {
                $this->addFlash('error', 'Fichier vide');
            }

            return $this->redirectToRoute('admin_pharmacie_dispo');
        }

        return $this->render('pharmacie/disponibilite_medicament.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return mixed
     */
    private function createPatientForm()
    {
        $form = $this->createForm(new SearchType(), null, [
            'action' => $this->generateUrl('admin_pharmacie_search'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit', ['label' => 'Rechercher']);

        return $form;
    }
}
