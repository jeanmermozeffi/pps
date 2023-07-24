<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\LigneAssurance;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use PS\GestionBundle\Service\RowAction;

/**
 * Ligneassurance controller.
 *
 */
class LigneAssuranceController extends Controller
{
    /**
     * Lists all ligneAssurance entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $liste_ligneAssurances = $em->getRepository('ParametreBundle:LigneAssurance')->findAll();

        $ligneAssurances  = $this->get('knp_paginator')->paginate(
            $liste_ligneAssurances,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('ligneassurance/index.html.twig', array(
            'ligneAssurances' => $ligneAssurances,
        ));
    }

    /**
     * Creates a new ligneAssurance entity.
     *
     */
    public function newAction(Request $request)
    {
        $ligneAssurance = new Ligneassurance();
        $form = $this->createForm('PS\ParametreBundle\Form\LigneAssuranceType', $ligneAssurance, array(
            'action' => $this->generateUrl('admin_parametre_ligneassurance_new'),
            'method' => 'POST'
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ligneAssurance);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_ligneassurance_index');
        }

        return $this->render('ligneassurance/new.html.twig', array(
            'ligneAssurance' => $ligneAssurance,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ligneAssurance entity.
     *
     */
    public function showAction(LigneAssurance $ligneAssurance)
    {
        $showForm = $this->createForm('PS\ParametreBundle\Form\LigneAssuranceType', $ligneAssurance);

        $deleteForm = $this->createDeleteForm($ligneAssurance);

        return $this->render('ligneassurance/show.html.twig', array(
            'ligneAssurance' => $ligneAssurance,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ligneAssurance entity.
     *
     */
    public function editAction(Request $request, LigneAssurance $ligneAssurance)
    {
        $deleteForm = $this->createDeleteForm($ligneAssurance);
        $editForm = $this->createForm('PS\ParametreBundle\Form\LigneAssuranceType', $ligneAssurance, array(
            'action' => $this->generateUrl('admin_parametre_ligneassurance_edit', array('id' => $ligneAssurance->getId())),
            'method' => 'POST'
        ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_parametre_ligneassurance_index');
        }

        return $this->render('ligneassurance/edit.html.twig', array(
            'ligneAssurance' => $ligneAssurance,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ligneAssurance entity.
     *
     */
    public function deleteAction(Request $request, LigneAssurance $ligneAssurance)
    {
        $form = $this->createDeleteForm($ligneAssurance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ligneAssurance);
            $em->flush();
        }

        return $this->redirectToRoute('admin_parametre_ligneassurance_index');
    }

    /**
     * Creates a form to delete a ligneAssurance entity.
     *
     * @param LigneAssurance $ligneAssurance The ligneAssurance entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(LigneAssurance $ligneAssurance)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametre_ligneassurance_delete', array('id' => $ligneAssurance->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
