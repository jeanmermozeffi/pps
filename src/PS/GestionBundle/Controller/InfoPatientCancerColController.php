<?php

namespace PS\GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PS\GestionBundle\Form\InfoPatientCancerColType;
use PS\GestionBundle\Entity\InfoPatientCancerCol;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Form\SearchType;
use PS\GestionBundle\Service\RowAction;

/**
 * Infopatientcancercol controller.
 *
 * @Route("/admin/gestion/depistage-cancer-col")
 */
class InfoPatientCancerColController extends Controller
{

    public function getCurrentPatient(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->getSession()->get('patient')) {
            return $em->getRepository(Patient::class)->find($request->getSession()->get('patient'));
        }
    }


    /**
     * @return mixed
     */
    private function createPatientForm()
    {
        $form = $this->createForm(SearchType::class, [
            'action' => $this->generateUrl('gestion_infopatientcancercol_search'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit', ['label' => 'Rechercher']);

        return $form;
    }

    /**
     *Function search
     * 
     * @Route("/search", name="gestion_infopatientcancercol_search")
     * @Method({"GET", "POST"})
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

            $patient = $em->getRepository('GestionBundle:Patient')->findOneBy(compact('identifiant', 'pin'));


            if ($patient && $patient->getSexe() == 'F') {
                $user = $this->getUser();
                $session->set('patient', $patient->getId());
                $route = $this->generateUrl('gestion_infopatientcancercol_new');


                return $this->redirect($route);

                /*if ($contact = $patient->getPersonne()->getSmsContact()) {
                        
                        $smsManager = $this->get('app.ps_sms');
                        $message    = "Votre historique médical est entrain d'être consulté par le Medecin %s";
                        
                        if ($assurance)  {
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


                        $label = $assurance ? $assurance->getNom() : $hopital->getNom();

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
                }*/
            } else {
                $this->addFlash(
                    'patient',
                    'Ce patient n\'existe pas dans la base de données! ou n\'est pas de sexe féminin'
                );
            }
        }

        return $this->render('consultation/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Lists all infoPatientCancerCol entities.
     *
     * @Route("/{id}/liste", name="gestion_infopatientcancercol_index")
     * @Route("/", name="gestion_infopatientcancercol_index_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request, Patient $patient = null)
    {
        $source = new Entity(InfoPatientCancerCol::class);

        $params = [];

        $grid = $this->get('grid');

        if ($patient) {
            $source->manipulateQuery(function ($qb) use ($patient) {
                return $qb->andWhere('_a.patient = :patient')->setParameter('patient', $patient);
            });
        }

        $grid->setSource($source);

        $grid->setRouteUrl(
            $patient ?
                $this->generateUrl('gestion_infopatientcancercol_index', ['id' => $patient->getId()]) :
                $this->generateUrl('gestion_infopatientcancercol_index_index')
        );

        $rowAction = new RowAction('Détails', 'gestion_infopatientcancercol_show');

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'gestion_infopatientcancercol_edit');
        $rowAction->setAttributes(['ajax' => false]);
        $grid->addRowAction($rowAction);

        /*$rowAction = new RowAction('Supprimer', 'gestion_infopatientcancercol_delete');
        $rowAction->setAttributes(['data-target' => '#stack2']);
        $grid->addRowAction($rowAction);*/

        return $grid->getGridResponse('gestion/infopatientcancercol/index.html.twig',[
            'patient'=>$patient
        ]);
    }

    /**
     * Creates a new InfoPatientCancerCol entity.
     *
     * @Route("/new", name="gestion_infopatientcancercol_new")
     * @Route("/{id}/new", name="gestion_infoatientcancercol_new_patient")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request,  Patient $patient = null)
    {
        $session = $request->getSession();
        $patient = $patient ? $patient : $this->getCurrentPatient($request);
        if (!$patient) {
            return $this->redirectToRoute('gestion_infopatientcancercol_search');
        }


        $infoPatientCancerCol = new InfoPatientCancerCol();
        $infoPatientCancerCol->setPatient($patient);
        $infoPatientCancerCol->setDateDepistageIva(new \DateTime());
        $form = $this->createForm(InfoPatientCancerColType::class, $infoPatientCancerCol, [
            'action' => $patient ?
                $this->generateUrl('gestion_infoatientcancercol_new_patient', ['id' => $patient->getId()])
                : $this->generateUrl('gestion_infoatientcancercol_new_patient'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $params = ['id' => $patient->getId()];
            $redirectRoute = 'gestion_infopatientcancercol_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($infoPatientCancerCol);
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


        return $this->render('gestion/infopatientcancercol/new.html.twig', [
            'infoPatientCancerCol' => $infoPatientCancerCol,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a InfoPatientCancerCol entity.
     *
     * @Route("/{id}/show", name="gestion_infopatientcancercol_show")
     * @Method("GET")
     */
    public function showAction(Request $request, InfoPatientCancerCol $infoPatientCancerCol)
    {
        $deleteForm = $this->createDeleteForm($infoPatientCancerCol);
        $showForm = $this->createForm(InfoPatientCancerColType::class, $infoPatientCancerCol);
        $showForm->handleRequest($request);


        return $this->render('gestion/infopatientcancercol/show.html.twig', [
            'infoPatientCancerCol' => $infoPatientCancerCol,
            'form' => $showForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing InfoPatientCancerCol entity.
     *
     * @Route("/{id}/edit", name="gestion_infopatientcancercol_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, InfoPatientCancerCol $infoPatientCancerCol)
    {
        //$deleteForm = $this->createDeleteForm($infoPatientCancerCol);
        $form = $this->createForm(InfoPatientCancerColType::class, $infoPatientCancerCol, [
            'action' => $this->generateUrl('gestion_infopatientcancercol_edit', ['id' => $infoPatientCancerCol->getId()]),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'gestion_infopatientcancercol_index';
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

        return $this->render('gestion/infopatientcancercol/edit.html.twig', [
            'infoPatientCancerCol' => $infoPatientCancerCol,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a InfoPatientCancerCol entity.
     *
     * @Route("/{id}/delete", name="gestion_infopatientcancercol_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, InfoPatientCancerCol $infoPatientCancerCol)
    {
        $form = $this->createDeleteForm($infoPatientCancerCol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($infoPatientCancerCol);
            $em->flush();

            $redirect = $this->generateUrl('gestion_infopatientcancercol_index');

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

        return $this->render('gestion/infopatientcancercol/delete.html.twig', [
            'infoPatientCancerCol' => $infoPatientCancerCol,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a InfoPatientCancerCol entity.
     *
     * @param InfoPatientCancerCol $infoPatientCancerCol The InfoPatientCancerCol entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(InfoPatientCancerCol $infoPatientCancerCol)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'gestion_infopatientcancercol_delete',
                    [
                        'id' => $infoPatientCancerCol->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm();
    }
}
