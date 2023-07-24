<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\GroupeSanguin;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;

/**
 * Groupesanguin controller.
 *
 */
class GroupeSanguinController extends Controller
{
    /**
     * Lists all groupeSanguin entities.
     *
     */
    public function indexAction(Request $request)
    {
        $source = new Entity('ParametreBundle:GroupeSanguin');

        $grid = $this->get('grid');

        $grid->setSource($source);


        $rowAction = new RowAction('Détails', 'admin_parametre_groupesanguin_show');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:GroupeSanguin:show', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_parametre_groupesanguin_edit');

        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:GroupeSanguin:edit', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'admin_parametre_groupesanguin_delete');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:GroupeSanguin:delete', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        return $grid->getGridResponse('groupesanguin/grid.html.twig');
    }

    /**
     * Creates a new groupeSanguin entity.
     *
     */
    public function newAction(Request $request)
    {
        $groupeSanguin = new Groupesanguin();
        $form = $this->createForm('PS\ParametreBundle\Form\GroupeSanguinType', $groupeSanguin, array(
            'action' => $this->generateUrl('admin_parametre_groupesanguin_new'),
            'method' => 'POST'
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($groupeSanguin);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_groupesanguin_index');
        }

        return $this->render('groupesanguin/new.html.twig', array(
            'groupeSanguin' => $groupeSanguin,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a groupeSanguin entity.
     *
     */
    public function showAction(GroupeSanguin $groupeSanguin)
    {
        $showForm = $this->createForm('PS\ParametreBundle\Form\GroupeSanguinType', $groupeSanguin);

        $deleteForm = $this->createDeleteForm($groupeSanguin);

        return $this->render('groupesanguin/show.html.twig', array(
            'groupeSanguin' => $groupeSanguin,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing groupeSanguin entity.
     *
     */
    public function editAction(Request $request, GroupeSanguin $groupeSanguin)
    {
        $deleteForm = $this->createDeleteForm($groupeSanguin);
        $editForm = $this->createForm('PS\ParametreBundle\Form\GroupeSanguinType', $groupeSanguin, array(
            'action' => $this->generateUrl('admin_parametre_groupesanguin_edit', array('id' => $groupeSanguin->getId())),
            'method' => 'POST'
        ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_parametre_groupesanguin_index');
        }

        return $this->render('groupesanguin/edit.html.twig', array(
            'groupeSanguin' => $groupeSanguin,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a groupeSanguin entity.
     *
     */
    public function deleteAction(Request $request, GroupeSanguin $groupeSanguin)
    {
        $form = $this->createDeleteForm($groupeSanguin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($groupeSanguin);
            $em->flush();
            return $this->redirectToRoute('admin_parametre_groupesanguin_index');
        }

        return $this->render('groupesanguin/delete.html.twig', ['form' => $form->createView(), 'groupeSanguin' => $groupeSanguin]);
    }

    /**
     * Creates a form to delete a groupeSanguin entity.
     *
     * @param GroupeSanguin $groupeSanguin The groupeSanguin entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(GroupeSanguin $groupeSanguin)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametre_groupesanguin_delete', array('id' => $groupeSanguin->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
