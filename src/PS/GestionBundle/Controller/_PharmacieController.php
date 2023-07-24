<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Entity\Consultation;
use PS\GestionBundle\Entity\LigneAnalyse;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Entity\Traitement;
use PS\GestionBundle\Form\ConsultationType;
use PS\GestionBundle\Form\PatientType;
use PS\GestionBundle\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Consultation controller.
 *
 */
class PharmacieController extends Controller
{

    /**
     * Lists all consultation entities.
     *
     */
    public function searchAction(Request $request)
    {
        $form = $this->createPatientForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $identifiant = $form->get('identifiant')->getData();

            $pin = $form->get('pin')->getData();

            $count = $em->getRepository('GestionBundle:Patient')->findByPass($identifiant, $pin);

            //var_dump($count[0][1]);die();
            if ($count[0][1] > 0) {
                $patient = $em->getRepository('GestionBundle:Patient')->findByParam($identifiant, $pin);

                if (!$patient) {
                    $this->addFlash(
                        'patient',
                        'Ce patient n\'existe pas dans la base de données!'
                    );
                } else {
                    return $this->redirectToRoute('admin_consultation_new', array('id' => $patient[0]->getId()));
                }
            } else {
                $this->addFlash(
                    'patient',
                    'Ce patient n\'existe pas dans la base de données!'
                );
            }
        }

        return $this->render('consultation/search.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Lists all consultation entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $consultations = $em->getRepository('GestionBundle:Consultation')->findAll();

        return $this->render('consultation/index.html.twig', array(
            'consultations' => $consultations,
        ));
    }

    /**
     * Creates a new consultation entity.
     *
     */
    public function newAction(Request $request, Patient $patient)
    {
        $consultation = new Consultation();
        $form = $this->createCreateForm($consultation, $patient);

        $em = $this->getDoctrine()->getManager();

        //print_r($patient);
        $medecin = $em->getRepository('GestionBundle:Medecin')->findPersoByParam($this->getUser()->getPersonne()->getId());

        //print_r($medecin[0]);
        $consultation->setPatient($patient);
        $consultation->setMedecin($medecin[0]);

        //var_dump($medecin[0]->getHopital()->getNom());die();

        $form->handleRequest($request);

        if ($request->isMethod('POST')) {

            //var_dump($consultation);die();
            if ($form->isSubmitted() && $form->isValid()) {

                $em = $this->getDoctrine()->getManager();

                $em->persist($consultation);
                $em->flush();

                $this->addFlash(
                    'new_consultation',
                    'Consultation enregistrée avec succès!'
                );

                return $this->redirectToRoute('admin_consultation_edit', array('id' => $patient->getId(), 'id1' => $consultation->getId()));
            }
        }

        return $this->render('consultation/new.html.twig', array(
            'consultation' => $consultation,
            'patient' => $patient,
            'form' => $form->createView(),
        ));
    }

    /**
     * Lists all consultation entities.
     *
     */
    public function listeAction(Request $request, Patient $patient)
    {
        $em = $this->getDoctrine()->getManager();

        $liste_consultations = $em->getRepository('GestionBundle:Consultation')->findByPatient($patient->getId());

        $consultations = $this->get('knp_paginator')->paginate(
            $liste_consultations,
            $request->query->get('page', 1)/*page number*/,
            5/*limit per page*/
        );

        return $this->render('consultation/liste.html.twig', array(
            'consultations' => $consultations,
            'patient' => $patient,
        ));
    }

    /**
     * Lists all consultation entities.
     *
     */
    public function historiqueAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $liste_consultations = null;
        $role = null;

        if ($this->getUser()->hasRole("ROLE_CUSTOMER")) {

            $liste_consultations = $em->getRepository('GestionBundle:Consultation')->findAllPatient($this->getUser()->getPersonne()->getId());
            $role = "patient";
        } elseif ($this->getUser()->hasRole("ROLE_MEDECIN")) {

            $liste_consultations = $em->getRepository('GestionBundle:Consultation')->findAllMedecin($this->getUser()->getPersonne()->getId());
            $role = "medecin";
        } else {

            $liste_consultations = $em->getRepository('GestionBundle:Consultation')->findAll();
            $role = "admin";
        }

        $consultations = $this->get('knp_paginator')->paginate(
            $liste_consultations,
            $request->query->get('page', 1)/*page number*/,
            5/*limit per page*/
        );

        return $this->render('consultation/historique.html.twig', array(
            'consultations' => $consultations,
            'role' => $role,
        ));
    }

    /**
     * Creates a form to create a Consultation entity.
     *
     * @param Consultation $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Consultation $entity, Patient $patient)
    {
        $form = $this->createForm(new ConsultationType(), $entity, array(
            'action' => $this->generateUrl('admin_consultation_new', array('id' => $patient->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer'));

        return $form;
    }

    private function createPatientForm()
    {
        $form = $this->createForm(new SearchType(), array(
            'action' => $this->generateUrl('admin_consultation_search'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Rechercher'));

        return $form;
    }

    /**
     *
     * @param Consultation $consultation The entity
     *
     * @return \Symfony\Component\Form\Form The form
     *
     */
    public function showAction(Request $request, Patient $patient, $id1)
    {
        $em = $this->getDoctrine()->getManager();
        $consultation = $em->getRepository('GestionBundle:Consultation')->find($id1);

        if (!$consultation) {
            throw $this->createNotFoundException('Unable to find Consultation entity.');
        }

        return $this->render('consultation/show.html.twig', array(
            'consultation' => $consultation,
            'patient' => $patient,
            //'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     *
     * @param Consultation $consultation The entity
     *
     * @return \Symfony\Component\Form\Form The form
     *
     */
    public function historiqueShowAction(Patient $patient, $id1)
    {
        $em = $this->getDoctrine()->getManager();
        $consultation = $em->getRepository('GestionBundle:Consultation')->find($id1);


        //$age = $patient->getAge();

        if (!$consultation) {
            throw $this->createNotFoundException('Unable to find Consultation entity.');
        }

        return $this->render('consultation/historique_show.html.twig', array(
            'consultation' => $consultation,
            'patient' => $patient,
        ));
    }

    /**
     * Displays a form to edit an existing consultation entity.
     *
     */
    public function editAction(Request $request, Patient $patient, $id1)
    {
        $em = $this->getDoctrine()->getManager();
        $consultation = $em->getRepository('GestionBundle:Consultation')->find($id1);

        if (!$consultation) {
            throw $this->createNotFoundException('Unable to find Consultation entity.');
        }

        $editForm = $this->createEditForm($patient, $consultation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'edit_consultation',
                'Consultation modifiée avec succès!'
            );

            return $this->redirectToRoute('admin_consultation_edit', array('id' => $patient->getId(), 'id1' => $consultation->getId()));
        }

        return $this->render('consultation/edit.html.twig', array(
            'consultation' => $consultation,
            'patient' => $patient,
            'edit_form' => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to create a Consultation entity.
     *
     * @param Consultation $consultation The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Patient $patient, Consultation $consultation)
    {
        $form = $this->createForm(new ConsultationType(), $consultation, array(
            'action' => $this->generateUrl('admin_consultation_edit', array('id' => $patient->getId(), 'id1' => $consultation->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }

    /**
     * Deletes a consultation entity.
     *
     */
    public function deleteAction(Request $request, Consultation $consultation)
    {
        $form = $this->createDeleteForm($consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($consultation);
            $em->flush();
        }

        return $this->redirectToRoute('admin_consultation_index');
    }

    /**
     * Creates a form to delete a consultation entity.
     *
     * @param Consultation $consultation The consultation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Consultation $consultation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_consultation_delete', array('id' => $consultation->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /*
 * ---------------------------- zone d'impression --------------------------------------------
 */

    public function previewConsultationAction(Patient $patient, $id1)
    {
        return $this->render('consultation/preview_consultation.html.twig', array("id" => $id1, "patient" => $patient));
    }

    /*
     * Impression Fiche de Consultation
     */
    public function imprimerConsultationAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $consultation = $em->getRepository('GestionBundle:Consultation')->find($id);

        //on stocke la vue à convertir en PDF, en n'oubliant pas les paramètres twig si la vue comporte des données dynamiques
        $html = $this->renderView('consultation/print_consultation.html.twig', array('consultation' => $consultation));


        //on appelle le service html2pdf
        $html2pdf = $this->get('html2pdf_factory')->create();
        //real : utilise la taille réelle
        $html2pdf->pdf->SetDisplayMode('default');
        //writeHTML va tout simplement prendre la vue stockée dans la variable $html pour la convertir en format PDF
        $html2pdf->writeHTML($html);
        //Output envoit le document PDF au navigateur internet
        $html2pdf->Output('Consultation-' . $id . '.pdf');

        return new Response();
    }

    public function previewOrdonnanceAction(Patient $patient, $id1)
    {
        return $this->render('consultation/preview_ordonnance.html.twig', array("id" => $id1, "patient" => $patient));
    }

    /*
     * Impression Ordonnance
     */
    public function imprimerOrdonnanceAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $consultation = $em->getRepository('GestionBundle:Consultation')->find($id);

        //$age = date_diff(new DateTime(), $consultation->getPatient()->getDateNaissance());

        //on stocke la vue à convertir en PDF, en n'oubliant pas les paramètres twig si la vue comporte des données dynamiques
        $html = $this->renderView('consultation/print_ordonnance.html.twig', array('consultation' => $consultation));



        //on appelle le service html2pdf
        $html2pdf = $this->get('html2pdf_factory')->create('P', 'A5');
        //real : utilise la taille réelle
        $html2pdf->pdf->SetDisplayMode('default');
        //writeHTML va tout simplement prendre la vue stockée dans la variable $html pour la convertir en format PDF
        $html2pdf->writeHTML($html);
        //Output envoit le document PDF au navigateur internet
        $html2pdf->Output('Ordonnance.pdf');

        return new Response();
    }

    public function previewAnalyseAction(Patient $patient, $id1)
    {
        return $this->render('consultation/preview_consultation.html.twig', array("id" => $id1, "patient" => $patient));
    }

    /*
     * Impression Fiche d'Analyse
     */
    public function imprimerAnalyseAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ParametreBundle:Demande')->find($id);
        $structure = $em->getRepository('ParametreBundle:Structure')->find(1);

        $manager = $this->container->get('doctrine')->getEntityManager();
        $query = $manager->createQueryBuilder();

        //on stocke la vue à convertir en PDF, en n'oubliant pas les paramètres twig si la vue comporte des données dynamiques
        $html = $this->renderView('ParametreBundle:Demande:demande.html.twig', array(
            'entities' => $entities,
            'structure' => $structure
        ));


        //on appelle le service html2pdf
        $html2pdf = $this->get('html2pdf_factory')->create();
        //real : utilise la taille réelle
        $html2pdf->pdf->SetDisplayMode('default');
        //writeHTML va tout simplement prendre la vue stockée dans la variable $html pour la convertir en format PDF
        $html2pdf->writeHTML($html);
        //Output envoit le document PDF au navigateur internet
        $html2pdf->Output('Demande-' . $id . '.pdf');

        return new Response();
    }
}
