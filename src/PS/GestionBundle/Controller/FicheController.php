<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Entity\Fiche;
use PS\GestionBundle\Entity\FicheConstante;
use PS\GestionBundle\Entity\FicheAnalyse;
use PS\GestionBundle\Entity\FicheComplication;
use PS\GestionBundle\Entity\FicheGc;
use PS\GestionBundle\Entity\FicheGlycemie;
use PS\GestionBundle\Entity\FicheAntecedent;
use PS\GestionBundle\Entity\Patient;
use Symfony\Component\HttpFoundation\Request;
use PS\GestionBundle\Form\FicheType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use PS\GestionBundle\Form\SearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Fiche controller.
 *
 * @Route("/admin/gestion/fiche")
 */
class FicheController extends Controller
{
    /** 
     * @Route("/search", name="gestion_fiche_search")
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
                    $message    = "Accès à votre profil pour vaccination par le Medecin/Infirmier %s";

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

                return $this->redirectToRoute('gestion_fiche_new');
            }
        }

        return $this->render('gestion/patient/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    /**
     * Lists all fiche entities.
     *
     * @Route("/liste/{patient}", name="gestion_fiche_liste")
     * @Method("GET")
     */
    public function listeAction(Request $request, Patient $patient = null)
    {
        $patient = $patient ? $patient: $this->getCurrentPatient($request);
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $liste_consultations = $em->getRepository(Fiche::class)->findByPatient($patient);

        $consultations = $this->get('knp_paginator')->paginate(
            $liste_consultations,
            $request->query->get('page', 1) /*page number*/,
            5/*limit per page*/
        );

        return $this->render('gestion/fiche/liste.html.twig', [
            'consultations' => $consultations,
            'patient'       => $patient,
        ]);
    }



   
     /**
     * Lists all fiche entities.
     *
     * @Route("/{id}/print", name="gestion_fiche_print")
     * @Method("GET")
     */
    public function printAction(Request $request, Fiche $fiche)
    {
        $html2pdf      = $this->get('html2pdf_factory')->create('L', 'A4', 'fr', false, 'UTF-8', [15, 2, 5, 5]);
        //real : utilise la taille réelle
        $html2pdf->pdf->SetDisplayMode('fullpage');
        //writeHTML va tout simplement prendre la vue stockée dans la variable $html pour la convertir en format PDF
        $html2pdf->writeHTML($this->renderView('gestion/fiche/print.html.twig', ['fiche' => $fiche]));
        //Output envoit le document PDF au navigateur internet
        $html2pdf->Output('FICHE_' . $fiche->getid() . '.pdf');

        return new Response();
    }


    /**
     * Lists all fiche entities.
     *
     * @Route("/", name="gestion_fiche_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $patients = $em->getRepository(Patient::class)->cpn($this->getUser()->getHopital());

        $patients = $this->get('knp_paginator')->paginate(
            $patients,
            $request->query->get('page', 1) /*page number*/,
            30/*limit per page*/
        );
        


        return $this->render('gestion/fiche/index.html.twig', ['patients' => $patients]);

        /*$source = new Entity(Fiche::class);

        $params = [];

        $grid = $this->get('grid');

        $grid->setSource($source);

        $user       = $this->getUser();
        $patient = $this->getPatient($request);

        $source->manipulateQuery(function ($qb) use ($patient, $user) {

            if (!$this->isGranted('ROLE_CUSTOMER')) {
                $qb->andWhere('_a.hopital = :hopital');
                $qb->setParameter('hopital', $user->getHopital());
            } else {
                $qb->andWhere('_a.patient = :patient');
                $qb->setParameter('patient', $patient);
            }
            
            $qb->orderBy('_a.date', 'DESC');

        });

        $grid->setRouteUrl($this->generateUrl('gestion_fiche_index'));


        $rowAction = new RowAction('Détails', 'gestion_fiche_print');
        $rowAction->setAttributes(['ajax' => false]);
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'gestion_fiche_edit');
        $rowAction->setAttributes(['ajax' => false]);
        $grid->addRowAction($rowAction);

            /*$rowAction = new RowAction('Supprimer', 'gestion_fiche_delete');
        $rowAction->setAttributes(['data-target' => '#stack2']);
        $grid->addRowAction($rowAction);*/
    


        return $grid->getGridResponse('gestion/fiche/index.html.twig');
    }

    public function getPatient(Request $request)
    {
        $session = $request->getSession();
        $em         = $this->getDoctrine()->getManager();
        return $session->get('patient') ?  $em->getRepository(Patient::class)->find($session->get('patient')): $this->getUser()->getPatient();
    }


     /**
     * Creates a new fiche entity.
     *
     * @Route("/historique/{patient}", name="gestion_fiche_histo")
     * @Method({"GET", "POST"})
     */
    public function historiqueAction(Request $request, Patient $patient)
    {
         $em         = $this->getDoctrine()->getManager();

        $cpns = $em->getRepository(Fiche::class)->findByPatient($patient);

        $cpns = $this->get('knp_paginator')->paginate(
            $cpns,
            $request->query->get('page', 1) /*page number*/,
            15/*limit per page*/
        );


        return $this->render('gestion/fiche/historique.html.twig', [
            'cpns' => $cpns,
            'patient' => $patient
        ]);
    }


    /**
     * Creates a new fiche entity.
     *
     * @Route("/new", name="gestion_fiche_new")
     * @Route("/new/{patient}", name="gestion_fiche_new_patient")
     * @Method({"GET", "POST"})
     * @Security("is_granted('ROLE_MEDECIN') or is_granted('ROLE_INFIRMIER')")
     */
    public function newAction(Request $request, Patient $patient = null)
    {
        $session = $request->getSession();
        $patient = $patient ?: $this->getPatient($request);



        if (!$patient) {
            $this->addFlash('patient', 'Veuillez renseigner un ID/PIN pour  continuer');

            return $this->redirect($this->generateUrl('gestion_fiche_search'));
        }
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $repFiche = $em->getRepository(Fiche::class);
        $constantes = FicheConstante::LIBELLES;
        $antecedents = FicheAntecedent::LIBELLES;
        $analyses = FicheAnalyse::LIBELLES;
        $gcs = FicheGc::LIBELLES;
        $complications = FicheComplication::LIBELLES;
        $glycemies = FicheGlycemie::LIBELLES;


       


        $fiche = new Fiche();
        $fiche->setPatient($patient);
        $fiche->setCpn($repFiche->cpn($patient));
        $fiche->setPersonne($user->getPersonne());
        $fiche->setHopital($user->getHopital());
        $fiche->setDate(new \DateTime());
        $fiche->setReference($em->getRepository(Fiche::class)->reference());

        //dump($fiche);exit;


        foreach ($constantes as $constante) {
            $_constante = new FicheConstante();
            $_constante->setLibelle($constante);
            $fiche->addConstante($_constante);
        }


        foreach ($antecedents as $antecedent) {
            $_antecedent = new FicheAntecedent();
            $_antecedent->setLibelle($antecedent);
            $fiche->addAntecedent($_antecedent);
        }


        foreach ($analyses as $analyse) {
            $_analyse = new FicheAnalyse();
            $_analyse->setLibelle($analyse);
            $fiche->addAnalysis($_analyse);
        }


        foreach ($glycemies as $glycemie) {
            $_glycemie = new FicheGlycemie();
            $_glycemie->setLibelle($glycemie);
            $fiche->addGlycemy($_glycemie);
        }


        foreach ($complications as $complication) {
            $_complication = new FicheComplication();
            $_complication->setLibelle($complication);
            $fiche->addComplication($_complication);
        }


        foreach ($gcs as $gc) {
            $_gc = new FicheGc();
            $_gc->setLibelle($gc);
            $fiche->addGc($_gc);
        }



        //$fiche->setHopital($user->getMedecin());

        $form = $this->createForm(FicheType::class, $fiche, [
                'action' => $patient ? $this->generateUrl('gestion_fiche_new_patient' , ['patient' => $patient->getId()]):$this->generateUrl('gestion_fiche_new') ,
                'method' => 'POST',
            ]);


        $form->handleRequest($request);

         

        
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'gestion_fiche_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
               
                $em->persist($fiche);
                $em->flush();
                $message       = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);
            } else {
                $message = $this->get('app.form_error')->all($form);
                $statut = 0;
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


        return $this->render('gestion/fiche/new.html.twig', [
            'fiche' => $fiche,
            'patient' => $patient,
            'constantes' => $constantes,
            'complications' => $complications,
            'gcs' => $gcs,
            'antecedents' => $antecedents,
            'analyses' => $analyses,
            'glycemies' => $glycemies,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a fiche entity.
     *
     * @Route("/{id}/show", name="gestion_fiche_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Fiche $fiche)
    {
           
        $form = $this->createForm(FicheType::class, $fiche);

        $constantes = FicheConstante::LIBELLES;
        $antecedents = FicheAntecedent::LIBELLES;
        $analyses = FicheAnalyse::LIBELLES;
        $gcs = FicheGc::LIBELLES;
        $complications = FicheComplication::LIBELLES;
        $glycemies = FicheGlycemie::LIBELLES;

    


    return $this->render('gestion/fiche/show.html.twig', [
            'fiche' => $fiche,
            'form' => $form->createView(),
            'constantes' => $constantes,
            'complications' => $complications,
            'gcs' => $gcs,
            'antecedents' => $antecedents,
            'analyses' => $analyses,
            'glycemies' => $glycemies,

                
        ]);
    }

    /**
     * Displays a form to edit an existing fiche entity.
     *
     * @Route("/{id}/edit", name="gestion_fiche_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Fiche $fiche)
    {
        //$deleteForm = $this->createDeleteForm($fiche);
        $form = $this->createForm(FicheType::class, $fiche, [
                'action' => $this->generateUrl('gestion_fiche_edit', ['id' => $fiche->getId()]),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);
       
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'gestion_fiche_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $message       = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);
            } else {
                $message = $this->get('app.form_error')->all($form);
                $statut = 0;
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

        return $this->render('gestion/fiche/edit.html.twig', [
            'fiche' => $fiche,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a fiche entity.
     *
     * @Route("/{id}/delete", name="gestion_fiche_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Fiche $fiche)
    {
        $form = $this->createDeleteForm($fiche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($fiche);
            $em->flush();

            $redirect = $this->generateUrl('gestion_fiche_index');

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

        return $this->render('gestion/fiche/delete.html.twig', [
            'fiche' => $fiche,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a fiche entity.
     *
     * @param Fiche $fiche The fiche entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Fiche $fiche)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'gestion_fiche_delete'
                ,   [
                        'id' => $fiche->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
