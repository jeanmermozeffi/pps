<?php

namespace PS\UtilisateurBundle\Controller;

use PS\UtilisateurBundle\Entity\Groupe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Groupe controller.
 *
 */
class GroupeController extends Controller
{
    /**
     * Lists all groupe entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $liste_groupes = $em->getRepository('UtilisateurBundle:Groupe')->findAll();

        $groupes  = $this->get('knp_paginator')->paginate(
            $liste_groupes,
            $request->query->get('page', 1)/*page number*/,10/*limit per page*/
        );

        return $this->render('groupe/index.html.twig', array(
            'groupes' => $groupes,
        ));
    }

    /**
     * Creates a new groupe entity.
     *
     */
    public function newAction(Request $request)
    {
        $groupe = new Groupe();
        $form = $this->createForm('PS\UtilisateurBundle\Form\GroupeType', $groupe, array(
                'action' => $this->generateUrl('admin_config_groupe_new'),
                'method' => 'POST'
            ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($groupe);
            $em->flush();

            return $this->redirectToRoute('admin_config_groupe_index');
        }

        return $this->render('groupe/new.html.twig', array(
            'groupe' => $groupe,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a groupe entity.
     *
     */
    public function showAction(Groupe $groupe)
    {
        $showForm = $this->createForm('PS\UtilisateurBundle\Form\GroupeType', $groupe);

        $deleteForm = $this->createDeleteForm($groupe);

        return $this->render('groupe/show.html.twig', array(
            'groupe' => $groupe,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing groupe entity.
     *
     */
    public function editAction(Request $request, Groupe $groupe)
    {
        $deleteForm = $this->createDeleteForm($groupe);
        $editForm = $this->createForm('PS\UtilisateurBundle\Form\GroupeType', $groupe, array(
                'action' => $this->generateUrl('admin_config_groupe_edit', array('id' => $groupe->getId())),
                'method' => 'POST'
            ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_config_groupe_index');
        }

        return $this->render('groupe/edit.html.twig', array(
            'groupe' => $groupe,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a groupe entity.
     *
     */
    public function deleteAction(Request $request, Groupe $groupe)
    {
        $form = $this->createDeleteForm($groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($groupe);
            $em->flush();
        }

        return $this->redirectToRoute('admin_config_groupe_index');
    }

    /**
     * Creates a form to delete a groupe entity.
     *
     * @param Groupe $groupe The groupe entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Groupe $groupe)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_config_groupe_delete', array('id' => $groupe->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
