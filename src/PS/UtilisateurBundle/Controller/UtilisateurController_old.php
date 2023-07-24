<?php

namespace PS\UtilisateurBundle\Controller;

use PS\UtilisateurBundle\Entity\Utilisateur;
use PS\UtilisateurBundle\Entity\Personne;
use PS\GestionBundle\Entity\Medecin;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Form\PatientType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Utilisateur controller.
 *
 */
class UtilisateurController extends Controller
{
    /**
     * Lists all utilisateur entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $liste_utilisateurs = $em->getRepository('UtilisateurBundle:Utilisateur')->findAll();

        $utilisateurs  = $this->get('knp_paginator')->paginate(
            $liste_utilisateurs,
            $request->query->get('page', 1)/*page number*/,10/*limit per page*/
        );

        return $this->render('utilisateur/index.html.twig', array(
            'utilisateurs' => $utilisateurs,
        ));
    }

    /**
     * Creates a new utilisateur entity.
     *
     */
    public function newAction(Request $request)
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm('PS\UtilisateurBundle\Form\UtilisateurType', $utilisateur, array(
                'action' => $this->generateUrl('admin_config_utilisateur_new'),
                'method' => 'POST'
            ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$em = $this->getDoctrine()->getManager();
            //$em->persist($utilisateur);
            //$em->flush();
            $personne = new Personne();

            $utilisateur->setPersonne($personne);
            $utilisateur->setEnabled(true);

            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($utilisateur);

            if ($utilisateur->hasRole("ROLE_MEDECIN")){
                $medecin = new Medecin();
                $medecin->setPersonne($utilisateur->getPersonne());

                $em = $this->getDoctrine()->getManager();
                $em->persist($medecin);
                $em->flush();
            }
            elseif ($utilisateur->hasRole("ROLE_CUSTOMER")){
                $patient = new Patient();
                $patient->setPersonne($utilisateur->getPersonne());

                $em = $this->getDoctrine()->getManager();
                $em->persist($patient);
                $em->flush();

                return $this->redirectToRoute('admin_config_utilisateur_patient', array('id'=>$patient->getId()));
            }

            return $this->redirectToRoute('admin_config_utilisateur_index');
        }

        return $this->render('utilisateur/new.html.twig', array(
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a utilisateur entity.
     *
     */
    public function showAction(Utilisateur $utilisateur)
    {
        $showForm = $this->createForm('PS\UtilisateurBundle\Form\UtilisateurType', $utilisateur);

        $deleteForm = $this->createDeleteForm($utilisateur);

        return $this->render('utilisateur/show.html.twig', array(
            'utilisateur' => $utilisateur,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing utilisateur entity.
     *
     */
    public function editAction(Request $request, Utilisateur $utilisateur)
    {
        $deleteForm = $this->createDeleteForm($utilisateur);
        $editForm = $this->createForm('PS\UtilisateurBundle\Form\UtilisateurType', $utilisateur, array(
                'action' => $this->generateUrl('admin_config_utilisateur_edit', array('id' => $utilisateur->getId())),
                'method' => 'POST',
			    'passwordRequired' => false,
            ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_config_utilisateur_index');
        }

        return $this->render('utilisateur/edit.html.twig', array(
            'utilisateur' => $utilisateur,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing patient entity.
     *
     */
    public function patientAction(Request $request, Patient $patient)
    {
        $editForm = $this->createEditForm($patient);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted()) {

            //    print_r($editForm);die();
            //var_dump($editForm);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_config_utilisateur_patient', array('id' => $patient->getId()));
        }

        return $this->render('utilisateur/patient.html.twig', array(
            'patient' => $patient,
            'edit_form' => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to create a Consultation entity.
     *
     * @param Patient $patient The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Patient $patient)
    {
        $form = $this->createForm(new PatientType(), $patient, array(
            'action' => $this->generateUrl('admin_config_utilisateur_patient', array('id' => $patient->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }

    /**
     * Deletes a utilisateur entity.
     *
     */
    public function deleteAction(Request $request, Utilisateur $utilisateur)
    {
        $form = $this->createDeleteForm($utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($utilisateur);
            $em->flush();
        }

        return $this->redirectToRoute('admin_config_utilisateur_index');
    }

    /**
     * Creates a form to delete a utilisateur entity.
     *
     * @param Utilisateur $utilisateur The utilisateur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Utilisateur $utilisateur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_config_utilisateur_delete', array('id' => $utilisateur->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
