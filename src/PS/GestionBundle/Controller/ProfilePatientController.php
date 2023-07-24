<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Form\PatientType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * ProfilePatient controller.
 *
 */
class ProfilePatientController extends Controller
{
    /**
     * Lists all patient entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $personne = $this->getUser()->getPersonne();

        $patient = $em->getRepository('GestionBundle:Patient')->findByPersonne($personne);

        $editForm = $this->createForm('PS\GestionBundle\Form\PatientType', $patient, array(
            'action' => $this->generateUrl('admin_gestion_profilepatient_index_edit', array('id' => $patient->getId())),
            'method' => 'POST'
        ));
        $editForm->handleRequest($request);

        return $this->render('GestionBundle:ProfilePatient:profile.html.twig', array(
            'patient' => $patient,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing patient entity.
     *
     */
    public function editAction(Request $request, Patient $patient)
    {
        $em = $this->getDoctrine()->getManager();

        $personne = $this->getUser()->getPersonne();

        //$patient = $em->getRepository('GestionBundle:Patient')->findByPersonne($personne);
        $patient = $em->getRepository('GestionBundle:Patient')->find(1);

        //var_dump($patient->getId());die();

        $editForm = $this->createForm('PS\GestionBundle\Form\PatientType', $patient, array(
            'action' => $this->generateUrl('admin_gestion_profilepatient_index_edit', array('id' => $patient->getId())),
            'method' => 'POST'
        ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_gestion_profilepatient_index_edit');
        }

        return $this->render('GestionBundle:ProfilePatient:profile.html.twig', array(
            'patient' => $patient,
            'form' => $editForm->createView(),
        ));
    }

}