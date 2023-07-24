<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\LigneVaccin;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use PS\GestionBundle\Service\RowAction;

/**
 * Lignevaccin controller.
 *
 */
class LigneVaccinController extends Controller
{
    /**
     * Lists all ligneVaccin entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $liste_ligneVaccins = $em->getRepository('ParametreBundle:LigneVaccin')->findAll();

        $ligneVaccins  = $this->get('knp_paginator')->paginate(
            $liste_ligneVaccins,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('lignevaccin/index.html.twig', array(
            'ligneVaccins' => $ligneVaccins,
        ));
    }

    /**
     * Creates a new ligneVaccin entity.
     *
     */
    public function newAction(Request $request)
    {
        $ligneVaccin = new Lignevaccin();
        $form = $this->createForm('PS\ParametreBundle\Form\LigneVaccinType', $ligneVaccin, array(
            'action' => $this->generateUrl('admin_parametre_lignevaccin_new'),
            'method' => 'POST'
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ligneVaccin);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_lignevaccin_index');
        }

        return $this->render('lignevaccin/new.html.twig', array(
            'ligneVaccin' => $ligneVaccin,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ligneVaccin entity.
     *
     */
    public function showAction(LigneVaccin $ligneVaccin)
    {
        $showForm = $this->createForm('PS\ParametreBundle\Form\LigneVaccinType', $ligneVaccin);

        $deleteForm = $this->createDeleteForm($ligneVaccin);

        return $this->render('lignevaccin/show.html.twig', array(
            'ligneVaccin' => $ligneVaccin,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ligneVaccin entity.
     *
     */
    public function editAction(Request $request, LigneVaccin $ligneVaccin)
    {
        $deleteForm = $this->createDeleteForm($ligneVaccin);
        $editForm = $this->createForm('PS\ParametreBundle\Form\LigneVaccinType', $ligneVaccin, array(
            'action' => $this->generateUrl('admin_parametre_lignevaccin_edit', array('id' => $ligneVaccin->getId())),
            'method' => 'POST'
        ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_parametre_lignevaccin_index');
        }

        return $this->render('lignevaccin/edit.html.twig', array(
            'ligneVaccin' => $ligneVaccin,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ligneVaccin entity.
     *
     */
    public function deleteAction(Request $request, LigneVaccin $ligneVaccin)
    {
        $form = $this->createDeleteForm($ligneVaccin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ligneVaccin);
            $em->flush();
        }

        return $this->redirectToRoute('admin_parametre_lignevaccin_index');
    }

    /**
     * Creates a form to delete a ligneVaccin entity.
     *
     * @param LigneVaccin $ligneVaccin The ligneVaccin entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(LigneVaccin $ligneVaccin)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametre_lignevaccin_delete', array('id' => $ligneVaccin->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
