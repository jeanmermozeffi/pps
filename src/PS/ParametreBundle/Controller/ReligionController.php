<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\Religion;
use PS\ParametreBundle\Form\ReligionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;

/**
 * Religion controller.
 *
 */
class ReligionController extends Controller
{
    /**
     * Lists all Religion entities.
     *
     */
    public function indexAction(Request $request)
    {
        $source = new Entity('ParametreBundle:Religion');
        $grid = $this->get('grid');
        $grid->setSource($source);

        $rowAction = new RowAction('Détails', 'admin_parametre_religion_show');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Religion:show', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_parametre_religion_edit');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Religion:edit', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'admin_parametre_religion_delete');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Religion:delete', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        return $grid->getGridResponse('ParametreBundle:Religion:grid.html.twig');
    }

    /**
     * Creates a new Religion entity.
     *
     */
    public function newAction(Request $request)
    {
        $religion = new Religion();
        $form = $this->createForm(ReligionType::class, $religion, array(
            'action' => $this->generateUrl('admin_parametre_religion_new'),
            'method' => 'POST',
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($religion);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_religion_index');
        }

        return $this->render('ParametreBundle:Religion:new.html.twig', array(
            'religion' =>  $religion,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Religion entity.
     *
     */
    public function showAction(Religion $religion)
    {
        $showForm = $this->createForm(ReligionType::class, $religion);

        $deleteForm = $this->createDeleteForm($religion);

        return $this->render('ParametreBundle:Religion:show.html.twig', array(
            'religion' =>  $religion,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Religion entity.
     *
     */
    public function editAction(Request $request, Religion $religion)
    {
        $deleteForm = $this->createDeleteForm($religion);
        $editForm = $this->createForm(ReligionType::class, $religion, array(
            'action' => $this->generateUrl('admin_parametre_religion_edit', array('id' => $religion->getId())),
            'method' => 'POST',
        ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_parametre_religion_index');
        }

        return $this->render('ParametreBundle:Religion:edit.html.twig', array(
            'religion' =>  $religion,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Religion entity.
     *
     */
    public function deleteAction(Request $request, Religion $religion)
    {
        $form = $this->createDeleteForm($religion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($religion);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_religion_index');
        }


        return $this->render('ParametreBundle:Religion:delete.html.twig', ['form' => $form->createView(), 'religion' =>  $religion]);
    }

    /**
     * Creates a form to delete a Religion entity.
     *
     * @param Religion $religion The Religion entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Religion $religion)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametre_religion_delete', array('id' => $religion->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
