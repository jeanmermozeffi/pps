<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Entity\FichierPatient;
use Symfony\Component\HttpFoundation\Request;
use PS\GestionBundle\Form\FichierPatientType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Entity\Medecin;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Form\SearchType;
use PS\GestionBundle\Service\RowAction;
use PS\ParametreBundle\Entity\Hopital;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Fichierpatient controller.
 *
 * @Route("/admin/gestion/fichier-patient")
 */
class FichierPatientController extends Controller
{

    /**
     * Lists all consultation entities.
     *
     * @Route("/search", name="gestion_fichierpatient_search")
     * @Method({"GET", "POST"})
     */
    public function searchAction(Request $request)
    {
        $session = $request->getSession();
        $session->remove('patient');
        $form = $this->createForm(SearchType::class, null, [
            'action'         => $this->generateUrl('gestion_fichierpatient_search'),
            'method'         => 'POST',
           
        ]);

        $form->add('submit',SubmitType::class, ['label' => 'Rechercher']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $identifiant = $form->get('identifiant')->getData();
           
            $pin         = $form->get('pin')->getData();

            $patient = $em->getRepository(Patient::class)->findOneBy(compact('identifiant', 'pin'));

            //19617726

            if (!$patient) {
                $this->addFlash(
                    'notice',
                    'Ce patient n\'existe pas dans la base de données!'
                );
            } else {
                $session->set('patient', $patient);
                return $this->redirectToRoute('gestion_fichierpatient_new');

            }

            return $this->redirectToRoute('gestion_fichierpatient_search');
        }

        return $this->render('gestion/patient/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * Lists all fichierPatient entities.
     *
     * @Route("/", name="gestion_fichierpatient_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $session = $request->getSession();

        $patient = $session->get('patient');

        if (!$patient) {
            $this->addFlash('notice', 'Veuillez rentrer le code PIN et l\'ID du patient avant de continuer');
            return $this->redirectToRoute('gestion_fichierpatient_search');
        }

        $em       = $this->getDoctrine()->getManager();
        $fichierQ = $em->getRepository(FichierPatient::class)->findAllPatient($patient->getId());

        $fichiers = $this->get('knp_paginator')->paginate(
            $fichierQ,
            $request->query->get('page', 1) /*page number*/,
            30/*limit per page*/
        );

        return $this->render('gestion/fichierpatient/index.html.twig', ['fichiers' => $fichiers, 'patient' => $patient]);
    
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
     * Creates a new fichierPatient entity.
     *
     * @Route("/new", name="gestion_fichierpatient_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $utilisateur = $this->getUser();
        $session = $request->getSession();
        if (!$patient = $session->get('patient')) {
            $this->addFlash('warning', 'Veuillez choisir un patient avant de continuer');
            return $this->redirectToRoute('gestion_fichepatient_search');
        }




        $em = $this->getDoctrine()->getManager();

        $patient = $em->getRepository(Patient::class)->find($patient->getId());
        $fichierPatient = new Fichierpatient();
        $fichierPatient->setPatient($patient);
        /*$hopital = $em->getRepository(Hopital::class)->find(1);
        $medecin = $em->getRepository(Medecin::class)->find(1);*/
        $fichierPatient->setHopital($utilisateur->getHopital());
        $fichierPatient->setMedecin($utilisateur->getMedecin());
        $form = $this->createForm($this->get('app.fichier_patient_type'), $fichierPatient, [
            'action' => $this->generateUrl('gestion_fichierpatient_new'),
            'method' => 'POST',
            'doc_options' => [
                'folder' => '../data/',
                'mime_types' => ['application/pdf', 'image/png', 'image/jpeg', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
                'upload_dir' => $this->getUploadDir('__fichiers')
            ]
        ]);
        $form->handleRequest($request);

         

        
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'gestion_fichierpatient_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                 $this->get('app.action_logger')
            ->add('Création nouveau fichier Patient', $patient);
                $em->persist($fichierPatient);
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


        return $this->render('gestion/fichierpatient/new.html.twig', [
            'fichierPatient' => $fichierPatient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a fichierPatient entity.
     *
     * @Route("/{id}/show", name="gestion_fichierpatient_show")
     * @Method("GET")
     */
    public function showAction(Request $request, FichierPatient $fichierPatient)
    {
            $deleteForm = $this->createDeleteForm($fichierPatient);
        $showForm = $this->createForm(FichierPatientType::class, $fichierPatient);
    $showForm->handleRequest($request);


    return $this->render('gestion/fichierpatient/show.html.twig', [
            'fichierPatient' => $fichierPatient,
            'show_form' => $showForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ]);
    }

    /**
     * Displays a form to edit an existing fichierPatient entity.
     *
     * @Route("/{id}/edit", name="gestion_fichierpatient_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, FichierPatient $fichierPatient)
    {
        //$deleteForm = $this->createDeleteForm($fichierPatient);
        $form = $this->createForm(FichierPatientType::class, $fichierPatient, [
                'action' => $this->generateUrl('gestion_fichierpatient_edit', ['id' => $fichierPatient->getId()]),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);
       
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'gestion_fichierpatient_index';
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

        return $this->render('gestion/fichierpatient/edit.html.twig', [
            'fichierPatient' => $fichierPatient,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a fichierPatient entity.
     *
     * @Route("/{id}/delete", name="gestion_fichierpatient_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, FichierPatient $fichierPatient)
    {
        $form = $this->createDeleteForm($fichierPatient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($fichierPatient);
            $em->flush();

            $redirect = $this->generateUrl('gestion_fichierpatient_index');

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

        return $this->render('gestion/fichierpatient/delete.html.twig', [
            'fichierPatient' => $fichierPatient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a fichierPatient entity.
     *
     * @param FichierPatient $fichierPatient The fichierPatient entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(FichierPatient $fichierPatient)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'gestion_fichierpatient_delete'
                ,   [
                        'id' => $fichierPatient->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
