<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Entity\Traitement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Traitement controller.
 *
 */
class TraitementController extends Controller
{
    /**
     * Lists all traitement entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $liste_traitements = $em->getRepository('GestionBundle:Traitement')->findAll();

        $traitements  = $this->get('knp_paginator')->paginate(
            $liste_traitements,
            $request->query->get('page', 1)/*page number*/,10/*limit per page*/
        );

        return $this->render('traitement/index.html.twig', array(
            'traitements' => $traitements,
        ));
    }

    /**
     * Creates a new traitement entity.
     *
     */
    public function newAction(Request $request)
    {
        $traitement = new Traitement();
        $form = $this->createForm('PS\GestionBundle\Form\TraitementType', $traitement, array(
                'action' => $this->generateUrl('admin_gestion_traitement_new'),
                'method' => 'POST'
            ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($traitement);
            $em->flush();

            return $this->redirectToRoute('admin_gestion_traitement_index');
        }

        return $this->render('traitement/new.html.twig', array(
            'traitement' => $traitement,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a traitement entity.
     *
     */
    public function showAction(Traitement $traitement)
    {
        $showForm = $this->createForm('PS\GestionBundle\Form\TraitementType', $traitement);

        $deleteForm = $this->createDeleteForm($traitement);

        return $this->render('traitement/show.html.twig', array(
            'traitement' => $traitement,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing traitement entity.
     *
     */
    public function editAction(Request $request, Traitement $traitement)
    {
        $deleteForm = $this->createDeleteForm($traitement);
        $editForm = $this->createForm('PS\GestionBundle\Form\TraitementType', $traitement, array(
                'action' => $this->generateUrl('admin_gestion_traitement_edit', array('id' => $traitement->getId())),
                'method' => 'POST'
            ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_gestion_traitement_index');
        }

        return $this->render('traitement/edit.html.twig', array(
            'traitement' => $traitement,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a traitement entity.
     *
     */
    public function deleteAction(Request $request, Traitement $traitement)
    {
        $form = $this->createDeleteForm($traitement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($traitement);
            $em->flush();
        }

        return $this->redirectToRoute('admin_gestion_traitement_index');
    }

    /**
     * Creates a form to delete a traitement entity.
     *
     * @param Traitement $traitement The traitement entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Traitement $traitement)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_gestion_traitement_delete', array('id' => $traitement->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
