<?php

namespace PS\GestionBundle\Controller;

use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Entity\FicheAffection;
use PS\GestionBundle\Entity\FicheExamen;
use PS\GestionBundle\Entity\FicheParamClinique;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Form\FicheAffectionType;
use PS\GestionBundle\Form\SearchType;
use PS\GestionBundle\Service\RowAction;
use PS\ParametreBundle\Entity\Affection;
use PS\ParametreBundle\Entity\Constante;
use PS\ParametreBundle\Entity\ListeExamen;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Ficheaffection controller.
 *
 * @Route("/admin/gestion/ficheaffection")
 */
class FicheAffectionController extends Controller
{

    /**
     * @Route("/search", name="gestion_ficheaffection_search")
     * @Method({"GET", "POST"})
     */
    public function searchAction(Request $request)
    {
        $session = $request->getSession();
        $session->remove('patient');
        $form = $this->createForm(SearchType::class, [
            'action' => $this->generateUrl('gestion_fiche_search'),
            'method' => 'POST',
            //'with_reference' => true
        ]);

        $form->add('submit', SubmitType::class, ['label' => 'Rechercher']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $identifiant = $form->get('identifiant')->getData();

            $pin = $form->get('pin')->getData();

            $patient = $em->getRepository(Patient::class)->findOneBy(compact('identifiant', 'pin'));

            //var_pmp($count[0][1]);die();

            if (!$patient) {
                $this->addFlash(
                    'patient',
                    'Ce patient n\'existe pas dans la base de données!'
                );
            } else {

                $session->set('patient', $patient->getId());
                $user = $this->getUser();

                if ($contact = $patient->getPersonne()->getSmsContact()) {

                    $smsManager = $this->get('app.ps_sms');
                    $message    = "Accès à votre profil pour consultation par le Medecin/Infirmier %s";

                    $user     = $this->getUser();
                    $personne = $user->getPersonne();
                    $nom      = $user->getUsername();
                    $hopital  = $user->getHopital();

                    if ($personne->getNomComplet()) {
                        $nom .= '(' . $personne->getNomComplet() . ')';
                    }

                    $this->get('app.action_logger')->add('Page de vaccination', $patient, true);

                    //$smsManager->send(sprintf($message, $nom, $label), $contact);
                }

                return $this->redirectToRoute('gestion_ficheaffection_liste', ['patient' => $patient->getId()]);
            }
        }

        return $this->render('gestion/patient/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Lists all ficheAffection entities.
     *
     * @Route("/", name="gestion_ficheaffection_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {

        $source = new Entity(FicheAffection::class);

        $source->manipulateQuery(function ($qb) {
            $qb->andWhere('_a.hopital = :hopital')->setParameter('hopital', $this->getUser()->getHopital());
        });

        $params = [];

        $grid = $this->get('grid');

        $grid->setSource($source);

        $grid->setRouteUrl($this->generateUrl('gestion_ficheaffection_index'));

        $rowAction = new RowAction('Détails', 'gestion_ficheaffection_show');

        $grid->addRowAction($rowAction);

        /*$rowAction = new RowAction('Modifier', 'gestion_ficheaffection_edit');
        $grid->addRowAction($rowAction);

        /*$rowAction = new RowAction('Supprimer', 'gestion_ficheaffection_delete');
        $rowAction->setAttributes(['data-target' => '#stack2']);
        $grid->addRowAction($rowAction);*/

        return $grid->getGridResponse('gestion/ficheaffection/index.html.twig');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getPatient(Request $request)
    {
        $session = $request->getSession();
        $em      = $this->getDoctrine()->getManager();

        return $session->get('patient') ? $em->getRepository(Patient::class)->find($session->get('patient')) : $this->getUser()->getPatient();
    }

    /**
     * Lists all fiche entities.
     *
     * @Route("/liste/{patient}", name="gestion_ficheaffection_liste")
     * @Method("GET")
     */
    public function listeAction(Request $request, Patient $patient = null)
    {
        $patient = $patient ? $patient : $this->getPatient($request);
        $em      = $this->getDoctrine()->getManager();
        $user    = $this->getUser();

        $liste_consultations = $em->getRepository(FicheAffection::class)->findByPatient($patient);

        $consultations = $this->get('knp_paginator')->paginate(
            $liste_consultations,
            $request->query->get('page', 1) /*page number*/,
            5/*limit per page*/
        );

        return $this->render('gestion/ficheaffection/liste.html.twig', [
            'consultations' => $consultations,
            'patient'       => $patient,
        ]);
    }

    /**
     * Creates a new ficheAffection entity.
     *
     * @Route("/new", name="gestion_ficheaffection_new")
     * @Route("/{id}/new", name="gestion_ficheaffection_new_patient")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Patient $patient = null)
    {
        $session = $request->getSession();
        $inUrl   = is_object($patient);
        $patient = $patient ?: $this->getPatient($request);

        $em = $this->getDoctrine()->getManager();

        if (!$patient) {
            $this->addFlash('patient', 'Veuillez renseigner un ID/PIN pour  continuer');

            return $this->redirect($this->generateUrl('gestion_ficheaffection_search'));
        }

        $lastFiche = $em->getRepository(FicheAffection::class)->last($patient);

        $user = $this->getUser();

        $ficheAffection = new FicheAffection();

        $constantes  = $em->getRepository(Constante::class)->findAll();
        $examens     = $em->getRepository(ListeExamen::class)->findAll();
        $biologiques = $em->getRepository(ListeExamen::class)->findBy(['categorie' => 1]);
        $cliniques   = $em->getRepository(ListeExamen::class)->findBy(['categorie' => 2]);
        foreach ($constantes as $constante) {
            $ficheAffection->addConstante((new FicheParamClinique)->setConstante($constante));
        }

        foreach ($examens as $examen) {
            $ficheAffection->addExamen((new FicheExamen)->setExamen($examen));
        }

        $ficheAffection->setPatient($patient);
        $ficheAffection->setDate(new \DateTime());
        $ficheAffection->setAffection($em->getRepository(Affection::class)->find(715));
        $ficheAffection->setMedecin($user->getMedecin());
        $ficheAffection->setHopital($user->getHopital());
        $form = $this->createForm(FicheAffectionType::class, $ficheAffection, [
            'action' => $inUrl ? $this->generateUrl('gestion_ficheaffection_new_patient', ['id' => $patient->getId()]) : $this->generateUrl('gestion_ficheaffection_new'),
            'method' => 'POST',
        ]);

        $form->get('cliniques')->setData($ficheAffection->getExamens()->filter(function ($e) {
            return $e->getExamen()->getCategorie()->getId() == 2;
        }));

        $form->get('biologiques')->setData($ficheAffection->getExamens()->filter(function ($e) {
            return $e->getExamen()->getCategorie()->getId() == 1;
        }));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $params   = [];

            if ($form->isValid()) {

                $em->persist($ficheAffection);
                $em->flush();
                $redirectRoute = 'gestion_ficheaffection_print_preview';
                $params        = ['id' => $ficheAffection->getId()];
                $redirect      = $this->generateUrl($redirectRoute, $params);

                $message = 'Opération effectuée avec succès';
                $statut  = 1;
                $this->addFlash('success', $message);
            } else {
                $params        = ['id' => $patient->getId()];
                $redirectRoute = 'gestion_ficheaffection_new';
                $redirect      = $this->generateUrl($redirectRoute, $params);
                $message       = $this->get('app.form_error')->all($form);
                $statut        = 0;
                $this->addFlash('warning', $message);
            }

            if ($request->isXmlHttpRequest()) {
                $response = compact('statut', 'message', 'redirect');

                return new JsonResponse($response);
            } else {

                if ($statut == 1) {
                    return $this->redirect($redirect);
                }
            }

        }

        return $this->render('gestion/ficheaffection/new.html.twig', [
            'ficheAffection' => $ficheAffection,
            'last_fiche'     => $lastFiche,
            'patient'        => $patient,
            'constantes'     => $constantes,
            'biologiques'    => $biologiques,
            'cliniques'      => $cliniques,
            'form'           => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a ficheAffection entity.
     *
     * @Route("/{id}/show", name="gestion_ficheaffection_show")
     * @Method("GET")
     */
    public function showAction(Request $request, FicheAffection $ficheAffection)
    {

        $form = $this->createForm(FicheAffectionType::class, $ficheAffection);
        $form->get('cliniques')->setData($ficheAffection->getExamens()->filter(function ($e) {
            return $e->getExamen()->getCategorie()->getId() == 2;
        }));

        $form->get('biologiques')->setData($ficheAffection->getExamens()->filter(function ($e) {
            return $e->getExamen()->getCategorie()->getId() == 1;
        }));

        return $this->render('gestion/ficheaffection/show.html.twig', [
            'ficheAffection' => $ficheAffection,
            'form'           => $showForm->createView(),
            'delete_form'    => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing ficheAffection entity.
     *
     * @Route("/{id}/edit", name="gestion_ficheaffection_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, FicheAffection $ficheAffection)
    {
        //$deleteForm = $this->createDeleteForm($ficheAffection);
        $form = $this->createForm(FicheAffectionType::class, $ficheAffection, [
            'action' => $this->generateUrl('gestion_ficheaffection_edit', ['id' => $ficheAffection->getId()]),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response      = [];
            $params        = [];
            $redirectRoute = 'gestion_ficheaffection_index';
            $redirect      = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
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
                if ($statut == 1) {
                    return $this->redirect($redirect);
                }
            }

        }

        return $this->render('gestion/ficheaffection/edit.html.twig', [
            'ficheAffection' => $ficheAffection,
            'form'           => $form->createView(),
        ]);
    }

    /**
     * Deletes a ficheAffection entity.
     *
     * @Route("/{id}/delete", name="gestion_ficheaffection_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, FicheAffection $ficheAffection)
    {
        $form = $this->createDeleteForm($ficheAffection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ficheAffection);
            $em->flush();

            $redirect = $this->generateUrl('gestion_ficheaffection_index');

            $message = 'Opération effectuée avec succès';

            $response = [
                'statut'   => 1,
                'message'  => $message,
                'redirect' => $redirect,
            ];

            $this->addFlash('success', $message);

            if (!$request->isXmlHttpRequest()) {
                return $this->redirect($redirect);
            } else {
                return new JsonResponse($response);
            }
        }

        return $this->render('gestion/ficheaffection/delete.html.twig', [
            'ficheAffection' => $ficheAffection,
            'form'           => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a ficheAffection entity.
     *
     * @param FicheAffection $ficheAffection The ficheAffection entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(FicheAffection $ficheAffection)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'gestion_ficheaffection_delete'
                    , [
                        'id' => $ficheAffection->getId(),
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Lists all fiche entities.
     *
     * @Route("/{id}/print_preview", name="gestion_ficheaffection_print_preview")
     * @Method("GET")
     */
    public function printPreviewAction(Request $request, FicheAffection $fiche)
    {
        $vars = ['fiche' => $fiche];

        return $this->render('gestion/ficheaffection/preview_print.html.twig', $vars);

    }

    /**
     * Lists all fiche entities.
     *
     * @Route("/{id}/print", name="gestion_ficheaffection_print")
     * @Method("GET")
     */
    public function printAction(Request $request, FicheAffection $fiche)
    {
        $vars = ['fiche' => $fiche];

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs      = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData          = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'orientation' => $options['orientation'] ?? 'L',
            'mode'        => 'utf-8',
            'fontDir'     => array_merge($fontDirs, [
                $options['fontDir'] ?? [],
            ]),
            'fontdata'    => $fontData + [
                'comfortaa' => [
                    'B' => 'Comfortaa-Bold.ttf',
                    'R' => 'Comfortaa-Regular.ttf',
                    'L' => 'Comfortaa-Light.ttf',
                ],
            ],
        ]);

        $mpdf->shrink_tables_to_fit = 1;

        $mpdf->WriteHTML($this->renderView('gestion/ficheaffection/print.html.twig', $vars));

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
}
