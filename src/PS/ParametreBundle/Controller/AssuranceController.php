<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\Assurance;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;

/**
 * Assurance controller.
 *
 */
class AssuranceController extends Controller
{
    /**
     * Lists all assurance entities.
     *
     */
    public function indexAction(Request $request)
    {
        $source = new Entity('ParametreBundle:Assurance');

        $grid = $this->get('grid');

        $grid->setSource($source);


        $rowAction = new RowAction('Détails', 'admin_parametre_assurance_show');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Assurance:show', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_parametre_assurance_edit');

        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Assurance:edit', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'admin_parametre_assurance_delete');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Assurance:delete', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        return $grid->getGridResponse('assurance/grid.html.twig');
    }

    /**
     * Creates a new assurance entity.
     *
     */
    public function newAction(Request $request)
    {
        $assurance = new Assurance();
        $form = $this->createForm('PS\ParametreBundle\Form\AssuranceType', $assurance, array(
            'action' => $this->generateUrl('admin_parametre_assurance_new'),
            'method' => 'POST'
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($assurance);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_assurance_index');
        }

        return $this->render('assurance/new.html.twig', array(
            'assurance' => $assurance,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a assurance entity.
     *
     */
    public function showAction(Assurance $assurance)
    {
        $showForm = $this->createForm('PS\ParametreBundle\Form\AssuranceType', $assurance);

        $deleteForm = $this->createDeleteForm($assurance);

        return $this->render('assurance/show.html.twig', array(
            'assurance' => $assurance,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing assurance entity.
     *
     */
    public function editAction(Request $request, Assurance $assurance)
    {
        $deleteForm = $this->createDeleteForm($assurance);
        $editForm = $this->createForm('PS\ParametreBundle\Form\AssuranceType', $assurance, array(
            'action' => $this->generateUrl('admin_parametre_assurance_edit', array('id' => $assurance->getId())),
            'method' => 'POST'
        ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_parametre_assurance_index');
        }

        return $this->render('assurance/edit.html.twig', array(
            'assurance' => $assurance,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a assurance entity.
     *
     */
    public function deleteAction(Request $request, Assurance $assurance)
    {
        $form = $this->createDeleteForm($assurance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($assurance);
            $em->flush();
            return $this->redirectToRoute('admin_parametre_assurance_index');
        }

        return $this->render('assurance/delete.html.twig', ['form' => $form->createView(), 'assurance' => $assurance]);
    }

    /**
     * Creates a form to delete a assurance entity.
     *
     * @param Assurance $assurance The assurance entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Assurance $assurance)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametre_assurance_delete', array('id' => $assurance->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
