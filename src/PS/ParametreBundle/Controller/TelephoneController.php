<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\Telephone;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use PS\GestionBundle\Service\RowAction;

/**
 * Telephone controller.
 *
 */
class TelephoneController extends Controller
{
    /**
     * Lists all telephone entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $liste_telephones = $em->getRepository('ParametreBundle:Telephone')->findAll();

        $telephones  = $this->get('knp_paginator')->paginate(
            $liste_telephones,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('telephone/index.html.twig', array(
            'telephones' => $telephones,
        ));
    }

    /**
     * Creates a new telephone entity.
     *
     */
    public function newAction(Request $request)
    {
        $telephone = new Telephone();
        $form = $this->createForm('PS\ParametreBundle\Form\TelephoneType', $telephone, array(
            'action' => $this->generateUrl('admin_parametre_telephone_new'),
            'method' => 'POST'
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($telephone);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_telephone_index');
        }

        return $this->render('telephone/new.html.twig', array(
            'telephone' => $telephone,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a telephone entity.
     *
     */
    public function showAction(Telephone $telephone)
    {
        $showForm = $this->createForm('PS\ParametreBundle\Form\TelephoneType', $telephone);

        $deleteForm = $this->createDeleteForm($telephone);

        return $this->render('telephone/show.html.twig', array(
            'telephone' => $telephone,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing telephone entity.
     *
     */
    public function editAction(Request $request, Telephone $telephone)
    {
        $deleteForm = $this->createDeleteForm($telephone);
        $editForm = $this->createForm('PS\ParametreBundle\Form\TelephoneType', $telephone, array(
            'action' => $this->generateUrl('admin_parametre_telephone_edit', array('id' => $telephone->getId())),
            'method' => 'POST'
        ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_parametre_telephone_index');
        }

        return $this->render('telephone/edit.html.twig', array(
            'telephone' => $telephone,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a telephone entity.
     *
     */
    public function deleteAction(Request $request, Telephone $telephone)
    {
        $form = $this->createDeleteForm($telephone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($telephone);
            $em->flush();
        }

        return $this->redirectToRoute('admin_parametre_telephone_index');
    }

    /**
     * Creates a form to delete a telephone entity.
     *
     * @param Telephone $telephone The telephone entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Telephone $telephone)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametre_telephone_delete', array('id' => $telephone->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
