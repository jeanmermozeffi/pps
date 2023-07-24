<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\Region;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;

/**
 * Region controller.
 *
 */
class RegionController extends Controller
{
    /**
     * Lists all region entities.
     *
     */
    public function indexAction(Request $request)
    {
        $source = new Entity('ParametreBundle:Region');

        $grid = $this->get('grid');

        $grid->setSource($source);


        $rowAction = new RowAction('Détails', 'admin_parametre_region_show');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Region:show', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_parametre_region_edit');

        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Region:edit', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'admin_parametre_region_delete');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Region:delete', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        return $grid->getGridResponse('region/grid.html.twig');
    }

    /**
     * Creates a new region entity.
     *
     */
    public function newAction(Request $request)
    {
        $region = new Region();
        $form = $this->createForm('PS\ParametreBundle\Form\RegionType', $region, array(
            'action' => $this->generateUrl('admin_parametre_region_new'),
            'method' => 'POST',
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($region);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_region_index');
        }

        return $this->render('region/new.html.twig', array(
            'region' => $region,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a region entity.
     *
     */
    public function showAction(Region $region)
    {
        $showForm = $this->createForm('PS\ParametreBundle\Form\RegionType', $region);

        $deleteForm = $this->createDeleteForm($region);

        return $this->render('region/show.html.twig', array(
            'region' => $region,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing region entity.
     *
     */
    public function editAction(Request $request, Region $region)
    {
        $deleteForm = $this->createDeleteForm($region);
        $editForm = $this->createForm('PS\ParametreBundle\Form\RegionType', $region, array(
            'action' => $this->generateUrl('admin_parametre_region_edit', array('id' => $region->getId())),
            'method' => 'POST'
        ));

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_parametre_region_index');
        }

        return $this->render('region/edit.html.twig', array(
            'region' => $region,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a region entity.
     *
     */
    public function deleteAction(Request $request, Region $region)
    {
        $form = $this->createDeleteForm($region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($region);
            $em->flush();
            return $this->redirectToRoute('admin_parametre_region_index');
        }

        return $this->render('region/delete.html.twig', ['form' => $form->createView(), 'region' => $region]);
    }

    /**
     * Creates a form to delete a region entity.
     *
     * @param Region $region The region entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Region $region)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametre_region_delete', array('id' => $region->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
