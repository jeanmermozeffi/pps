<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\Specialite;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Entity\Medecin;
use PS\GestionBundle\Service\RowAction;

/**
 * Specialite controller.
 *
 */
class SpecialiteController extends Controller
{

    public function listeAction(Request $request)
    {
        $corporate = $this->getUser()->getPersonne()->getCorporate();
        if ($corporate) {
            $oldSpecialites = $corporate->getSpecialites()->toArray();
        }
        $em = $this->getDoctrine()->getManager();
        $specialites = $this->get('knp_paginator')->paginate(
            $em->getRepository(Specialite::class)->createQueryBuilder('u'),
            $request->query->get('page', 1) /*page number*/,
            20/*limit per page*/
        );

        if ($request->isMethod('POST')) {
            $_data = $request->request->get('specialite');
        }

        return $this->render('specialite/liste.html.twig', [
            'specialites' => $specialites,
        ]);
    }
    /**
     * Lists all specialite entities.
     *
     */
    public function indexAction(Request $request)
    {
        $source = new Entity('ParametreBundle:Specialite');

        $grid = $this->get('grid');

        $grid->setSource($source);


        $rowAction = new RowAction('Détails', 'admin_parametre_specialite_show');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Specialite:show', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_parametre_specialite_edit');

        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Specialite:edit', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'admin_parametre_specialite_delete');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Specialite:delete', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        return $grid->getGridResponse('specialite/grid.html.twig');
    }

    /**
     * Creates a new specialite entity.
     *
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $specialite = new Specialite();
        $form = $this->createForm('PS\ParametreBundle\Form\SpecialiteType', $specialite, array(
            'action' => $this->generateUrl('admin_parametre_specialite_new'),
            'method' => 'POST'
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($specialite);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_specialite_index');
        }

        return $this->render('specialite/new.html.twig', array(
            'specialite' => $specialite,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a specialite entity.
     *
     */
    public function showAction(Specialite $specialite)
    {
        $showForm = $this->createForm('PS\ParametreBundle\Form\SpecialiteType', $specialite);

        $deleteForm = $this->createDeleteForm($specialite);

        return $this->render('specialite/show.html.twig', array(
            'specialite' => $specialite,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing specialite entity.
     *
     */
    public function editAction(Request $request, Specialite $specialite)
    {
        $deleteForm = $this->createDeleteForm($specialite);
        $editForm = $this->createForm('PS\ParametreBundle\Form\SpecialiteType', $specialite, array(
            'action' => $this->generateUrl('admin_parametre_specialite_edit', array('id' => $specialite->getId())),
            'method' => 'POST'
        ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_parametre_specialite_index');
        }

        return $this->render('specialite/edit.html.twig', array(
            'specialite' => $specialite,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a specialite entity.
     *
     */
    public function deleteAction(Request $request, Specialite $specialite)
    {
        $form = $this->createDeleteForm($specialite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($specialite);
            $em->flush();
            return $this->redirectToRoute('admin_parametre_specialite_index');
        }


        return $this->render('specialite/delete.html.twig', ['form' => $form->createView(), 'specialite' => $specialite]);
    }

    public function medecinAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if (!$this->isGranted('ROLE_IS_MEDICAL', $user)) {
            $user = null;
        }

        $medecins = $em->getRepository(Medecin::class)->findBySpecialite($request->query->get('specialite'), $user);

        $response = [];

        foreach ($medecins as $medecin) {
            $response[] = ['id' => $medecin->getId(), 'name' => $medecin->getPersonne()->getNomComplet() . ' (' . $medecin->getPersonne()->getContact() . ')'];
        }

        return new JsonResponse($response);
    }

    /**
     * Creates a form to delete a specialite entity.
     *
     * @param Specialite $specialite The specialite entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Specialite $specialite)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametre_specialite_delete', array('id' => $specialite->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
