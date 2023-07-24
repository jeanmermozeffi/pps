<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\Medicament;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;

/**
 * Medicament controller.
 *
 */
class MedicamentController extends Controller
{
    /**
     * Lists all medicament entities.
     *
     */
    public function indexAction(Request $request)
    {
        $source = new Entity('ParametreBundle:Medicament');

        $grid = $this->get('grid');

        $grid->setSource($source);


        $rowAction = new RowAction('Détails', 'admin_parametre_medicament_show');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Medicament:show', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_parametre_medicament_edit');

        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Medicament:edit', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'admin_parametre_medicament_delete');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Medicament:delete', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        return $grid->getGridResponse('medicament/grid.html.twig');
    }

    /**
     * Creates a new medicament entity.
     *
     */
    public function newAction(Request $request)
    {
        $medicament = new Medicament();
        $form = $this->createForm('PS\ParametreBundle\Form\MedicamentType', $medicament, array(
            'action' => $this->generateUrl('admin_parametre_medicament_new'),
            'method' => 'POST'
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($medicament);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_medicament_index');
        }

        return $this->render('medicament/new.html.twig', array(
            'medicament' => $medicament,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a medicament entity.
     *
     */
    public function showAction(Medicament $medicament)
    {
        $showForm = $this->createForm('PS\ParametreBundle\Form\MedicamentType', $medicament);

        $deleteForm = $this->createDeleteForm($medicament);

        return $this->render('medicament/show.html.twig', array(
            'medicament' => $medicament,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing medicament entity.
     *
     */
    public function editAction(Request $request, Medicament $medicament)
    {
        $deleteForm = $this->createDeleteForm($medicament);
        $editForm = $this->createForm('PS\ParametreBundle\Form\MedicamentType', $medicament, array(
            'action' => $this->generateUrl('admin_parametre_medicament_edit', array('id' => $medicament->getId())),
            'method' => 'POST'
        ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_parametre_medicament_index');
        }

        return $this->render('medicament/edit.html.twig', array(
            'medicament' => $medicament,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a medicament entity.
     *
     */
    public function deleteAction(Request $request, Medicament $medicament)
    {
        $form = $this->createDeleteForm($medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($medicament);
            $em->flush();
            return $this->redirectToRoute('admin_parametre_medicament_index');
        }

        return $this->render('medicament/delete.html.twig', array(
            'medicament' => $medicament,
            'form' => $form->createView()
        ));
    }

    /**
     * Creates a form to delete a medicament entity.
     *
     * @param Medicament $medicament The medicament entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Medicament $medicament)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametre_medicament_delete', array('id' => $medicament->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
