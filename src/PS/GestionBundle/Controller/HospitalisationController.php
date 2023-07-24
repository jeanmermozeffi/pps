<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Entity\Hospitalisation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Hospitalisation controller.
 *
 */
class HospitalisationController extends Controller
{
    /**
     * Lists all hospitalisation entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $liste_hospitalisations = $em->getRepository('GestionBundle:Hospitalisation')->findAll();

        $hospitalisations  = $this->get('knp_paginator')->paginate(
            $liste_hospitalisations,
            $request->query->get('page', 1)/*page number*/,10/*limit per page*/
        );

        return $this->render('hospitalisation/index.html.twig', array(
            'hospitalisations' => $hospitalisations,
        ));
    }

    /**
     * Creates a new hospitalisation entity.
     *
     */
    public function newAction(Request $request)
    {
        $hospitalisation = new Hospitalisation();
        $form = $this->createForm('PS\GestionBundle\Form\HospitalisationType', $hospitalisation, array(
                'action' => $this->generateUrl('admin_gestion_hospitalisation_new'),
                'method' => 'POST'
            ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($hospitalisation);
            $em->flush();

            return $this->redirectToRoute('admin_gestion_hospitalisation_index');
        }

        return $this->render('hospitalisation/new.html.twig', array(
            'hospitalisation' => $hospitalisation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a hospitalisation entity.
     *
     */
    public function showAction(Hospitalisation $hospitalisation)
    {
        $showForm = $this->createForm('PS\GestionBundle\Form\HospitalisationType', $hospitalisation);

        $deleteForm = $this->createDeleteForm($hospitalisation);

        return $this->render('hospitalisation/show.html.twig', array(
            'hospitalisation' => $hospitalisation,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing hospitalisation entity.
     *
     */
    public function editAction(Request $request, Hospitalisation $hospitalisation)
    {
        $deleteForm = $this->createDeleteForm($hospitalisation);
        $editForm = $this->createForm('PS\GestionBundle\Form\HospitalisationType', $hospitalisation, array(
                'action' => $this->generateUrl('admin_gestion_hospitalisation_edit', array('id' => $hospitalisation->getId())),
                'method' => 'POST'
            ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_gestion_hospitalisation_index');
        }

        return $this->render('hospitalisation/edit.html.twig', array(
            'hospitalisation' => $hospitalisation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a hospitalisation entity.
     *
     */
    public function deleteAction(Request $request, Hospitalisation $hospitalisation)
    {
        $form = $this->createDeleteForm($hospitalisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($hospitalisation);
            $em->flush();
        }

        return $this->redirectToRoute('admin_gestion_hospitalisation_index');
    }

    /**
     * Creates a form to delete a hospitalisation entity.
     *
     * @param Hospitalisation $hospitalisation The hospitalisation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Hospitalisation $hospitalisation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_gestion_hospitalisation_delete', array('id' => $hospitalisation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
