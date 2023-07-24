<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\Vaccin;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;

/**
 * Vaccin controller.
 *
 */
class VaccinController extends Controller
{
    /**
     * Lists all vaccin entities.
     *
     */
    public function indexAction(Request $request)
    {
        $source = new Entity('ParametreBundle:Vaccin');

        $grid = $this->get('grid');

        $grid->setSource($source);


        $rowAction = new RowAction('Détails', 'admin_parametre_vaccin_show');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Vaccin:show', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_parametre_vaccin_edit');

        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Vaccin:edit', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'admin_parametre_vaccin_delete');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Vaccin:delete', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        return $grid->getGridResponse('vaccin/grid.html.twig');
    }

    /**
     * Creates a new vaccin entity.
     *
     */
    public function newAction(Request $request)
    {
        $vaccin = new Vaccin();
        $form = $this->createForm('PS\ParametreBundle\Form\VaccinType', $vaccin, array(
            'action' => $this->generateUrl('admin_parametre_vaccin_new'),
            'method' => 'POST'
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($vaccin);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_vaccin_index');
        }

        return $this->render('vaccin/new.html.twig', array(
            'vaccin' => $vaccin,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a vaccin entity.
     *
     */
    public function showAction(Vaccin $vaccin)
    {
        $showForm = $this->createForm('PS\ParametreBundle\Form\VaccinType', $vaccin);

        $deleteForm = $this->createDeleteForm($vaccin);

        return $this->render('vaccin/show.html.twig', array(
            'vaccin' => $vaccin,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing vaccin entity.
     *
     */
    public function editAction(Request $request, Vaccin $vaccin)
    {
        $deleteForm = $this->createDeleteForm($vaccin);
        $editForm = $this->createForm('PS\ParametreBundle\Form\VaccinType', $vaccin, array(
            'action' => $this->generateUrl('admin_parametre_vaccin_edit', array('id' => $vaccin->getId())),
            'method' => 'POST'
        ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_parametre_vaccin_index');
        }

        return $this->render('vaccin/edit.html.twig', array(
            'vaccin' => $vaccin,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a vaccin entity.
     *
     */
    public function deleteAction(Request $request, Vaccin $vaccin)
    {
        $form = $this->createDeleteForm($vaccin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($vaccin);
            $em->flush();
            return $this->redirectToRoute('admin_parametre_vaccin_index');
        }

        return $this->render('vaccin/delete.html.twig', ['form' => $form->createView(), 'vaccin' => $vaccin]);
    }

    /**
     * Creates a form to delete a vaccin entity.
     *
     * @param Vaccin $vaccin The vaccin entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Vaccin $vaccin)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametre_vaccin_delete', array('id' => $vaccin->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
