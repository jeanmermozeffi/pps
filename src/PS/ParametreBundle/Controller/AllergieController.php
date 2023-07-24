<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\Allergie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;



/**
 * Allergie controller.
 *
 */
class AllergieController extends Controller
{
    /**
     * Lists all allergie entities.
     *
     */
    public function indexAction(Request $request)
    {
        $source = new Entity('ParametreBundle:Allergie');

        $grid = $this->get('grid');

        $grid->setSource($source);


        $rowAction = new RowAction('Détails', 'admin_parametre_allergie_show');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Allergie:show', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_parametre_allergie_edit');

        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Allergie:edit', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'admin_parametre_allergie_delete');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Allergie:delete', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        return $grid->getGridResponse('allergie/grid.html.twig');
    }

    /**
     * Creates a new allergie entity.
     *
     */
    public function newAction(Request $request)
    {
        $allergie = new Allergie();
        $form = $this->createForm('PS\ParametreBundle\Form\AllergieType', $allergie, array(
            'action' => $this->generateUrl('admin_parametre_allergie_new'),
            'method' => 'POST'
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($allergie);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_allergie_index');
        }

        return $this->render('allergie/new.html.twig', array(
            'allergie' => $allergie,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a allergie entity.
     *
     */
    public function showAction(Allergie $allergie)
    {
        $showForm = $this->createForm('PS\ParametreBundle\Form\AllergieType', $allergie);

        $deleteForm = $this->createDeleteForm($allergie);

        return $this->render('allergie/show.html.twig', array(
            'allergie' => $allergie,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing allergie entity.
     *
     */
    public function editAction(Request $request, Allergie $allergie)
    {
        $deleteForm = $this->createDeleteForm($allergie);
        $editForm = $this->createForm('PS\ParametreBundle\Form\AllergieType', $allergie, array(
            'action' => $this->generateUrl('admin_parametre_allergie_edit', array('id' => $allergie->getId())),
            'method' => 'POST'
        ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_parametre_allergie_index');
        }

        return $this->render('allergie/edit.html.twig', array(
            'allergie' => $allergie,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a allergie entity.
     *
     */
    public function deleteAction(Request $request, Allergie $allergie)
    {
        $form = $this->createDeleteForm($allergie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($allergie);
            $em->flush();
            return $this->redirectToRoute('admin_parametre_allergie_index');
        }

        return $this->render('allergie/delete.html.twig', ['allergie' => $allergie, 'form' => $form->createView()]);
    }

    /**
     * Creates a form to delete a allergie entity.
     *
     * @param Allergie $allergie The allergie entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Allergie $allergie)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametre_allergie_delete', array('id' => $allergie->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
