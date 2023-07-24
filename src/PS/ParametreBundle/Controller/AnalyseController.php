<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\Analyse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use PS\GestionBundle\Service\RowAction;

/**
 * Analyse controller.
 *
 */
class AnalyseController extends Controller
{
    /**
     * Lists all analyse entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $liste_analyses = $em->getRepository('ParametreBundle:Analyse')->findAll();

        $analyses  = $this->get('knp_paginator')->paginate(
            $liste_analyses,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('analyse/index.html.twig', array(
            'analyses' => $analyses,
        ));
    }

    /**
     * Creates a new analyse entity.
     *
     */
    public function newAction(Request $request)
    {
        $analyse = new Analyse();
        $form = $this->createForm('PS\ParametreBundle\Form\AnalyseType', $analyse, array(
            'action' => $this->generateUrl('admin_parametre_analyse_new'),
            'method' => 'POST'
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($analyse);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_analyse_index');
        }

        return $this->render('analyse/new.html.twig', array(
            'analyse' => $analyse,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a analyse entity.
     *
     */
    public function showAction(Analyse $analyse)
    {
        $showForm = $this->createForm('PS\ParametreBundle\Form\AnalyseType', $analyse);

        $deleteForm = $this->createDeleteForm($analyse);

        return $this->render('analyse/show.html.twig', array(
            'analyse' => $analyse,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing analyse entity.
     *
     */
    public function editAction(Request $request, Analyse $analyse)
    {
        $deleteForm = $this->createDeleteForm($analyse);
        $editForm = $this->createForm('PS\ParametreBundle\Form\AnalyseType', $analyse, array(
            'action' => $this->generateUrl('admin_parametre_analyse_edit', array('id' => $analyse->getId())),
            'method' => 'POST'
        ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_parametre_analyse_index');
        }

        return $this->render('analyse/edit.html.twig', array(
            'analyse' => $analyse,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a analyse entity.
     *
     */
    public function deleteAction(Request $request, Analyse $analyse)
    {
        $form = $this->createDeleteForm($analyse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($analyse);
            $em->flush();
        }

        return $this->redirectToRoute('admin_parametre_analyse_index');
    }

    /**
     * Creates a form to delete a analyse entity.
     *
     * @param Analyse $analyse The analyse entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Analyse $analyse)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametre_analyse_delete', array('id' => $analyse->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
