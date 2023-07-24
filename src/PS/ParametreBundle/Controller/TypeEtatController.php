<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\TypeEtat;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use PS\ParametreBundle\Form\TypeEtatType;

/**
 * TypeEtat controller.
 *
 */
class TypeEtatController extends Controller
{
    /**
     * Lists all TypeEtat entities.
     *
     */
    public function indexAction(Request $request)
    {
        $source = new Entity(TypeEtat::class);

        $grid = $this->get('grid');

        $grid->setSource($source);


        $rowAction = new RowAction('Détails', 'admin_parametre_type_etat_show');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:TypeEtat:show', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_parametre_type_etat_edit');

        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:TypeEtat:edit', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'admin_parametre_type_etat_delete');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:TypeEtat:delete', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        return $grid->getGridResponse('type_etat/grid.html.twig');
    }

    /**
     * Creates a new TypeEtat entity.
     *
     */
    public function newAction(Request $request)
    {
        $typeEtat = new TypeEtat();
        $form = $this->createForm(TypeEtatType::class, $typeEtat, array(
            'action' => $this->generateUrl('admin_parametre_type_etat_new'),
            'method' => 'POST',
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typeEtat);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_type_etat_index');
        }

        return $this->render('type_etat/new.html.twig', array(
            'type_etat' => $typeEtat,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a TypeEtat entity.
     *
     */
    public function showAction(TypeEtat $typeEtat)
    {
        $showForm = $this->createForm(TypeEtatType::class, $typeEtat);

        $deleteForm = $this->createDeleteForm($typeEtat);

        return $this->render('type_etat/show.html.twig', array(
            'type_etat' => $typeEtat,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing TypeEtat entity.
     *
     */
    public function editAction(Request $request, TypeEtat $typeEtat)
    {
        $deleteForm = $this->createDeleteForm($typeEtat);
        $editForm = $this->createForm(TypeEtatType::class, $typeEtat, array(
            'action' => $this->generateUrl('admin_parametre_type_etat_edit', array('id' => $typeEtat->getId())),
            'method' => 'POST'
        ));

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_parametre_type_etat_index');
        }

        return $this->render('type_etat/edit.html.twig', array(
            'type_etat' => $typeEtat,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a TypeEtat entity.
     *
     */
    public function deleteAction(Request $request, TypeEtat $typeEtat)
    {
        $form = $this->createDeleteForm($typeEtat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($typeEtat);
            $em->flush();
            return $this->redirectToRoute('admin_parametre_type_etat_index');
        }

        return $this->render('type_etat/delete.html.twig', ['form' => $form->createView(), 'type_etat' => $typeEtat,]);
    }

    /**
     * Creates a form to delete a TypeEtat entity.
     *
     * @param TypeEtat $typeEtat The TypeEtat entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TypeEtat $typeEtat)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametre_type_etat_delete', array('id' => $typeEtat->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
