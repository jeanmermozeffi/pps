<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\Nationalite;
use PS\ParametreBundle\Form\NationaliteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;

/**
 * Nationalite controller.
 *
 */
class NationaliteController extends Controller
{
    /**
     * Lists all Nationalite entities.
     *
     */
    public function indexAction(Request $request)
    {
        $source = new Entity(Nationalite::class);
        $grid = $this->get('grid');
        $grid->setSource($source);

        $rowAction = new RowAction('Détails', 'admin_parametre_nationalite_show');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Nationalite:show', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_parametre_nationalite_edit');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Nationalite:edit', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'admin_parametre_nationalite_delete');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Nationalite:delete', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        return $grid->getGridResponse('ParametreBundle:Nationalite:grid.html.twig');
    }

    /**
     * Creates a new Nationalite entity.
     *
     */
    public function newAction(Request $request)
    {
        $nationalite = new Nationalite();
        $form = $this->createForm(NationaliteType::class, $nationalite, array(
            'action' => $this->generateUrl('admin_parametre_nationalite_new'),
            'method' => 'POST',
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($nationalite);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_nationalite_index');
        }

        return $this->render('ParametreBundle:Nationalite:new.html.twig', array(
            'nationalite' =>  $nationalite,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Nationalite entity.
     *
     */
    public function showAction(Nationalite $nationalite)
    {
        $showForm = $this->createForm(NationaliteType::class, $nationalite);

        $deleteForm = $this->createDeleteForm($nationalite);

        return $this->render('ParametreBundle:Nationalite:show.html.twig', array(
            'nationalite' =>  $nationalite,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Nationalite entity.
     *
     */
    public function editAction(Request $request, Nationalite $nationalite)
    {
        $deleteForm = $this->createDeleteForm($nationalite);
        $editForm = $this->createForm(NationaliteType::class, $nationalite, array(
            'action' => $this->generateUrl('admin_parametre_nationalite_edit', array('id' => $nationalite->getId())),
            'method' => 'POST',
        ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_parametre_nationalite_index');
        }

        return $this->render('ParametreBundle:Nationalite:edit.html.twig', array(
            'nationalite' =>  $nationalite,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Nationalite entity.
     *
     */
    public function deleteAction(Request $request, Nationalite $nationalite)
    {
        $form = $this->createDeleteForm($nationalite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($nationalite);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_nationalite_index');
        }


        return $this->render('ParametreBundle:Nationalite:delete.html.twig', ['form' => $form->createView(), 'nationalite' =>  $nationalite]);
    }

    /**
     * Creates a form to delete a Nationalite entity.
     *
     * @param Nationalite $nationalite The Nationalite entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Nationalite $nationalite)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametre_nationalite_delete', array('id' => $nationalite->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
