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
     * @Route("/impresion", name="app_badge_edittion_print", methods={"POST"}, options={"expose"=true})
     */
    public function print(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $patientsIds = $data['selectedValues'];

        // Récupérez les entités Patient à partir des identifiants sélectionnés
        $selectedPatients = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->findBy(['id' => $data['selectedValues']]);


        // Générez le contenu HTML pour chaque patient
        $htmlContent = '';
        foreach ($selectedPatients as $patient) {
            $vars = ['patient' => $patient];
            $template = 'patient/badge.html.twig';
            $htmlContent .= $this->renderView($template, $vars);
        }

        // Utilisez mPDF pour générer un PDF unique
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'orientation' => 'P',
            'fontDir' => array_merge($fontDirs, [$this->getParameter('bundle_dir') . '/public/fonts/montserrat/']),
            'fontdata' => $fontData + [
                'Montserrat' => [
                    'B' => 'Montserrat-Bold.ttf',
                    'R' => 'Montserrat-Regular.ttf',
                    'L' => 'Montserrat-Light.ttf',
                ],
            ],
            'default_font' => 'Montserrat',
            'img_dpi' => 300,
            'dpi' => 300,
            'format' => 'A4',
        ]);

        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->WriteHTML($htmlContent);

        // Envoyez le PDF en réponse
        return new Response($mpdf->Output('Badge_All_Selected_Patients.pdf', 'I'));
        
        // Retournez une réponse JSON pour indiquer le succès ou l'échec de l'opération
        // return new JsonResponse(['success' => true]);
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
            'patients' =>$patients,
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
     * @Route("/{id}", name="app_badge_edittion_show", methods={"GET"})
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
     * @Route("/{id}", name="app_badge_edittion_delete", methods={"POST"})
     */
    public function delete(Request $request, BadgeEdittion $badgeEdittion, BadgeEdittionRepository $badgeEdittionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$badgeEdittion->getId(), $request->request->get('_token'))) {
            $badgeEdittionRepository->remove($badgeEdittion, true);
        }

        return $this->redirectToRoute('app_badge_edittion_index', [], Response::HTTP_SEE_OTHER);
    }
}
