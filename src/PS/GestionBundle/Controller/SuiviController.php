<?php

namespace PS\GestionBundle\Controller;

use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Entity\Suivi;
use PS\GestionBundle\Form\SearchType;
use PS\GestionBundle\Form\SuiviType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Suivi controller.
 *
 * @Route("/admin/gestion/patient/suivi")
 */
class SuiviController extends Controller
{
    /**
     * Lists all suivi entities.
     *
     * @Route("/", name="gestion_suivi_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em       = $this->getDoctrine()->getManager();
        $patients = $em->getRepository(Patient::class)->suivis($this->getUser()->getHopital());

        $patients = $this->get('knp_paginator')->paginate(
            $patients,
            $request->query->get('page', 1) /*page number*/,
            30/*limit per page*/
        );

        return $this->render('gestion/suivi/index.html.twig', ['patients' => $patients]);
    }

    /**
     * Creates a new suivi entity.
     *
     * @Route("/new", name="gestion_suivi_new")
     * @Route("/{patient}/new", name="gestion_suivi_new_patient")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Patient $patient = null)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $patient = $patient ?: $session->get('patient');
        $user    = $this->getUser();

        if (!$patient) {
            $this->addFlash('patient', 'Veuillez renseigner un ID/PIN pour  continuer');

            return $this->redirect($this->generateUrl('gestion_suivi_search'));
        }


        $patient = $em->getRepository(Patient::class)->find($patient);

        $suivi = new Suivi();
        $suivi->setPatient($patient);
        $suivi->setHopital($user->getHopital());
        $suivi->setMedecin($user->getMedecin());
        $suivi->setDate(new \DateTime());

        $form = $this->createForm(SuiviType::class, $suivi, [
            'action'  => $this->generateUrl('gestion_suivi_new', ['patient' => $patient->getId()]),
            'method'  => 'POST',
            'patient' => $patient,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response      = [];
            $params        = ['id' => $patient->getId()];
            $redirectRoute = 'gestion_suivi_historique_patient';
            $redirect      = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                
                $em->persist($suivi);
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

                if ($statut == 1) {
                    return $this->redirect($redirect);
                }
            }

        }

        return $this->render('gestion/suivi/new.html.twig', [
            'suivi' => $suivi,
            'form'  => $form->createView(),

        ]);
    }

    /**
     * Finds and displays a suivi entity.
     *
     * @Route("/{id}/show", name="gestion_suivi_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Suivi $suivi)
    {

        return $this->render('gestion/suivi/show.html.twig', [
            'suivi' => $suivi,
            'patient' => $suivi->getPatient()

        ]);
    }

    /**
     * Displays a form to edit an existing suivi entity.
     *
     * @Route("/{id}/edit", name="gestion_suivi_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Suivi $suivi)
    {
        //$deleteForm = $this->createDeleteForm($suivi);
        $form = $this->createForm(SuiviType::class, $suivi, [
            'action' => $this->generateUrl('gestion_suivi_edit', ['id' => $suivi->getId()]),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response      = [];
            $params        = [];
            $redirectRoute = 'gestion_suivi_index';
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

        return $this->render('gestion/suivi/edit.html.twig', [
            'suivi' => $suivi,
            'form'  => $form->createView(),
        ]);
    }

    /**
     * Deletes a suivi entity.
     *
     * @Route("/{id}/delete", name="gestion_suivi_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Suivi $suivi)
    {
        $form = $this->createDeleteForm($suivi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($suivi);
            $em->flush();

            $redirect = $this->generateUrl('gestion_suivi_index');

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

        return $this->render('gestion/suivi/delete.html.twig', [
            'suivi' => $suivi,
            'form'  => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a suivi entity.
     *
     * @param Suivi $suivi The suivi entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Suivi $suivi)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'gestion_suivi_delete'
                    , [
                        'id' => $suivi->getId(),
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Deletes a suivi entity.
     *
     * @Route("/historique", name="gestion_suivi_historique")
     * @Route("/{id}/historique", name="gestion_suivi_historique_patient")
     * @Method({"GET"})
     */
    public function historiqueAction(Request $request, Patient $patient = null)
    {
        $em      = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $patient = $patient ?: $em->getRepository(Patient::class)->find($session->get('patient'));
        $user    = $this->getUser();

        if (!$patient) {
            $this->addFlash('patient', 'Veuillez renseigner un ID/PIN pour  continuer');

            return $this->redirect($this->generateUrl('gestion_suivi_search'));
        }

        $suivis = $em->getRepository(Suivi::class)->findByPatient($patient);

        $suivis = $this->get('knp_paginator')->paginate(
            $suivis,
            $request->query->get('page', 1) /*page number*/,
            15/*limit per page*/
        );

        return $this->render('gestion/suivi/historique.html.twig', [
            'suivis'  => $suivis,
            'patient' => $patient,
        ]);
    }

    /**
     * @Route("/search", name="gestion_suivi_search")
     * @Method({"GET", "POST"})
     * Lists all consultation entities.
     * @Security("is_granted('ROLE_MEDECIN') or is_granted('ROLE_INFIRMIER')")
     */
    public function searchAction(Request $request)
    {
        $session = $request->getSession();
        $session->remove('patient');
        $form = $this->createForm(SearchType::class, [
            'action'         => $this->generateUrl('gestion_suivi_search'),
            'method'         => 'POST',
            'with_reference' => true,
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
                    $message    = "Accès à votre profil pour suivi par le Medecin/Infirmier %s";

                    $user     = $this->getUser();
                    $personne = $user->getPersonne();
                    $nom      = $user->getUsername();
                    $hopital  = $user->getHopital();

                    if ($personne->getNomComplet()) {
                        $nom .= '(' . $personne->getNomComplet() . ')';
                    }

                    $this->get('app.action_logger')->add('Suivi', $patient, true);

                    //$smsManager->send(sprintf($message, $nom, $label), $contact);
                }

                return $this->redirectToRoute('gestion_suivi_historique');
            }
        }

        return $this->render('gestion/patient/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
