<?php

namespace PS\GestionBundle\Controller;


use PS\GestionBundle\Entity\BadgeEdittion;
use App\Repository\BadgeEdittionRepository;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Form\BadgeEdittionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * @Route("admin/badge/edittion")
 */
class BadgeEdittionController extends Controller
{
    /**
     * @Route("/", name="app_badge_edittion_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('badge_edittion/index.html.twig', [
            // 'badge_edittions' => $badgeEdittionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/impresion", name="app_badge_edittion_print", methods={"GET", "POST"}, options={"expose"=true})
     */
    public function print(Request $request): Response
    {
        $badgesEditor  = $this->get('app.badges_editor');
        $session = $request->getSession();
        $redirectRoute = 'app_badge_edittion_dashboard';

        if ($request->isXmlHttpRequest()) {
            $redirectRoute = 'app_badge_edittion_print';

            $data = json_decode($request->getContent(), true);
            $patientsIds = $data['selectedValues'];

            // Vérifiez si le tableau $patientsIds est vide
            if (empty($patientsIds)) {
                $redirect = $this->generateUrl('app_badge_edittion_dashboard');
                $message = 'Veuillez sélectionner des patients';
                $statut = false;

                $response = compact('statut', 'redirect', 'message');
                return new JsonResponse($response);
            }

            $session->set('patientsIds', $patientsIds);
            $statut = true;
            $message = "Vous avez imprimé tous les clients sélectionnés !";

            $redirect = $this->generateUrl($redirectRoute);

            $response = compact('statut', 'redirect', 'message');
            return new JsonResponse($response);
        }

        $patientsIds = $session->get('patientsIds');

        // Vérifiez si le tableau $patientsIds est vide
        if (empty($patientsIds)) {
            $this->addFlash("pvc", "Veuillez sélectionner des patients");
            return $this->redirectToRoute('app_badge_edittion_dashboard');
        }

        // Récupérez les entités Patient à partir des identifiants sélectionnés
        $selectedPatients = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->findBy(['id' => $patientsIds]);

        $badgesEditor->printCartePVC($selectedPatients);

        $redirect = $this->generateUrl($redirectRoute);

        return $this->redirectToRoute($redirect);
    }


    /**
     * @Route("/dashboard", name="app_badge_edittion_dashboard", methods={"GET"})
     */
    public function dashboard(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $patientRepositoty = $em->getRepository(Patient::class);

        $listePatients = $patientRepositoty->findAll();

        $patients = $this->get('knp_paginator')->paginate(
            $listePatients,
            $request->query->get('page', 1) /*page number*/,
            50/*limit per page*/
        );

        return $this->render('badge_edittion/dashboard.html.twig', [
            'patients' => $patients,
        ]);
    }

    /**
     * @Route("/new", name="app_badge_edittion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, BadgeEdittionRepository $badgeEdittionRepository): Response
    {
        $badgeEdittion = new BadgeEdittion();
        $form = $this->createForm(BadgeEdittionType::class, $badgeEdittion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $badgeEdittionRepository->add($badgeEdittion, true);

            return $this->redirectToRoute('app_badge_edittion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('badge_edittion/new.html.twig', [
            'badge_edittion' => $badgeEdittion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/show", name="app_badge_edittion_show", methods={"GET"})
     */
    public function show(BadgeEdittion $badgeEdittion): Response
    {
        return $this->render('badge_edittion/show.html.twig', [
            'badge_edittion' => $badgeEdittion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_badge_edittion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, BadgeEdittion $badgeEdittion, BadgeEdittionRepository $badgeEdittionRepository): Response
    {
        $form = $this->createForm(BadgeEdittionType::class, $badgeEdittion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $badgeEdittionRepository->add($badgeEdittion, true);

            return $this->redirectToRoute('app_badge_edittion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('badge_edittion/edit.html.twig', [
            'badge_edittion' => $badgeEdittion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="app_badge_edittion_delete", methods={"POST"})
     */
    public function delete(Request $request, BadgeEdittion $badgeEdittion, BadgeEdittionRepository $badgeEdittionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $badgeEdittion->getId(), $request->request->get('_token'))) {
            $badgeEdittionRepository->remove($badgeEdittion, true);
        }

        return $this->redirectToRoute('app_badge_edittion_index', [], Response::HTTP_SEE_OTHER);
    }
}