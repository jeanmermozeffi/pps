<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Entity\LigneAnalyse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Ligneanalyse controller.
 *
 */
class LigneAnalyseController extends Controller
{
    /**
     * Lists all ligneAnalyse entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $liste_ligneAnalyses = $em->getRepository('GestionBundle:LigneAnalyse')->findAll();

        $ligneAnalyses  = $this->get('knp_paginator')->paginate(
            $liste_ligneAnalyses,
            $request->query->get('page', 1)/*page number*/,10/*limit per page*/
        );

        return $this->render('ligneanalyse/index.html.twig', array(
            'ligneAnalyses' => $ligneAnalyses,
        ));
    }

    /**
     * Creates a new ligneAnalyse entity.
     *
     */
    public function newAction(Request $request)
    {
        $ligneAnalyse = new Ligneanalyse();
        $form = $this->createForm('PS\GestionBundle\Form\LigneAnalyseType', $ligneAnalyse, array(
                'action' => $this->generateUrl('admin_gestion_ligneanalyse_new'),
                'method' => 'POST'
            ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ligneAnalyse);
            $em->flush();

            return $this->redirectToRoute('admin_gestion_ligneanalyse_index');
        }

        return $this->render('ligneanalyse/new.html.twig', array(
            'ligneAnalyse' => $ligneAnalyse,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ligneAnalyse entity.
     *
     */
    public function showAction(LigneAnalyse $ligneAnalyse)
    {
        $showForm = $this->createForm('PS\GestionBundle\Form\LigneAnalyseType', $ligneAnalyse);

        $deleteForm = $this->createDeleteForm($ligneAnalyse);

        return $this->render('ligneanalyse/show.html.twig', array(
            'ligneAnalyse' => $ligneAnalyse,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ligneAnalyse entity.
     *
     */
    public function editAction(Request $request, LigneAnalyse $ligneAnalyse)
    {
        $deleteForm = $this->createDeleteForm($ligneAnalyse);
        $editForm = $this->createForm('PS\GestionBundle\Form\LigneAnalyseType', $ligneAnalyse, array(
                'action' => $this->generateUrl('admin_gestion_ligneanalyse_edit', array('id' => $ligneAnalyse->getId())),
                'method' => 'POST'
            ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_gestion_ligneanalyse_index');
        }

        return $this->render('ligneanalyse/edit.html.twig', array(
            'ligneAnalyse' => $ligneAnalyse,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ligneAnalyse entity.
     *
     */
    public function deleteAction(Request $request, LigneAnalyse $ligneAnalyse)
    {
        $form = $this->createDeleteForm($ligneAnalyse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ligneAnalyse);
            $em->flush();
        }

        return $this->redirectToRoute('admin_gestion_ligneanalyse_index');
    }

    /**
     * Creates a form to delete a ligneAnalyse entity.
     *
     * @param LigneAnalyse $ligneAnalyse The ligneAnalyse entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(LigneAnalyse $ligneAnalyse)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_gestion_ligneanalyse_delete', array('id' => $ligneAnalyse->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
