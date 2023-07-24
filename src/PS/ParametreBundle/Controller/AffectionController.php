<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\Affection;
use PS\ParametreBundle\Form\AffectionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;

/**
 * Affection controller.
 *
 */
class AffectionController extends Controller
{
    /**
     * Lists all affection entities.
     *
     */
    public function indexAction(Request $request)
    {
        $source = new Entity('ParametreBundle:Affection');

        $grid = $this->get('grid');

        $grid->setSource($source);


        $rowAction = new RowAction('Détails', 'admin_parametre_affection_show');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Affection:show', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_parametre_affection_edit');

        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Affection:edit', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'admin_parametre_affection_delete');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Affection:delete', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        return $grid->getGridResponse('affection/grid.html.twig');
    }

    /**
     * Creates a new affection entity.
     *
     */
    public function newAction(Request $request)
    {
        $affection = new Affection();
        $form = $this->createCreateForm($affection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($affection);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_affection_index', array('id' => $affection->getId()));
        }

        return $this->render('affection/new.html.twig', array(
            'affection' => $affection,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Affection entity.
     *
     * @param Affection $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Affection $entity)
    {
        $form = $this->createForm(new AffectionType(), $entity, array(
            'action' => $this->generateUrl('admin_parametre_affection_new'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer'));

        return $form;
    }

    /**
     * Finds and displays a affection entity.
     *
     */
    public function showAction(Affection $affection)
    {
        //$deleteForm = $this->createDeleteForm($affection);

        return $this->render('affection/show.html.twig', array(
            'affection' => $affection,
            //'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing affection entity.
     *
     */
    public function editAction(Request $request, Affection $affection)
    {
        //$deleteForm = $this->createDeleteForm($affection);
        //$editForm = $this->createForm('PS\ParametreBundle\Form\AffectionType', $affection);
        $editForm = $this->createEditForm($affection);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_parametre_affection_index', array('id' => $affection->getId()));
        }

        return $this->render('affection/edit.html.twig', array(
            'affection' => $affection,
            'edit_form' => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Affection entity.
     *
     * @param Affection $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Affection $entity)
    {
        $form = $this->createForm(new AffectionType(), $entity, array(
            'action' => $this->generateUrl('admin_parametre_affection_edit', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }

    /**
     * Deletes a affection entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $affection = $em->getRepository('ParametreBundle:Affection')->find($id);
        $form = $this->createDeleteForm($affection);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


            $em->remove($affection);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_affection_index');
        }

        return $this->render('affection/delete.html.twig', ['affection' => $affection, 'form' => $form->createView()]);
    }

    /**
     * Creates a form to delete a affection entity.
     *
     * @param Affection $affection The affection entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Affection $affection)
    {
        $form = $this->createForm(new AffectionType(), $affection, array(
            'action' => $this->generateUrl('admin_parametre_affection_delete', array('id' => $affection->getId())),
            'method' => 'DELETE',
        ));

        $form->add('submit', 'submit', array('label' => 'Supprimer'));

        return $form;
        /*return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametre_affection_delete', array('id' => $affection->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;*/
    }
}
