<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\Attribut;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use PS\ParametreBundle\Form\AttributType;

/**
 * Attribut controller.
 *
 */
class AttributController extends Controller
{
    /**
     * Lists all Attribut entities.
     *
     */
    public function indexAction(Request $request)
    {
        $source = new Entity('ParametreBundle:Attribut');

        $grid = $this->get('grid');

        $grid->setSource($source);


        $rowAction = new RowAction('Détails', 'admin_parametre_attribut_show');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Attribut:show', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_parametre_attribut_edit');

        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Attribut:edit', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'admin_parametre_attribut_delete');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Attribut:delete', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        return $grid->getGridResponse('attribut/grid.html.twig');
    }

    /**
     * Creates a new Attribut entity.
     *
     */
    public function newAction(Request $request)
    {
        $attribut = new Attribut();
        $form = $this->createForm(AttributType::class, $attribut, array(
            'action' => $this->generateUrl('admin_parametre_attribut_new'),
            'method' => 'POST',
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($attribut);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_attribut_index');
        }

        return $this->render('attribut/new.html.twig', array(
            'attribut' => $attribut,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Attribut entity.
     *
     */
    public function showAction(Attribut $attribut)
    {
        $showForm = $this->createForm(AttributType::class, $attribut);

        $deleteForm = $this->createDeleteForm($attribut);

        return $this->render('attribut/show.html.twig', array(
            'attribut' => $attribut,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Attribut entity.
     *
     */
    public function editAction(Request $request, Attribut $attribut)
    {
        $deleteForm = $this->createDeleteForm($attribut);
        $editForm = $this->createForm(AttributType::class, $attribut, array(
            'action' => $this->generateUrl('admin_parametre_attribut_edit', array('id' => $attribut->getId())),
            'method' => 'POST'
        ));

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_parametre_attribut_index');
        }

        return $this->render('attribut/edit.html.twig', array(
            'attribut' => $attribut,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Attribut entity.
     *
     */
    public function deleteAction(Request $request, Attribut $attribut)
    {
        $form = $this->createDeleteForm($attribut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($attribut);
            $em->flush();
            return $this->redirectToRoute('admin_parametre_attribut_index');
        }

        return $this->render('attribut/delete.html.twig', ['form' => $form->createView(), 'attribut' => $attribut,]);
    }

    /**
     * Creates a form to delete a Attribut entity.
     *
     * @param Attribut $attribut The Attribut entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Attribut $attribut)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametre_attribut_delete', array('id' => $attribut->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
