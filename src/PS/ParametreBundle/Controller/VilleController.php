<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\Ville;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;

/**
 * Ville controller.
 *
 */
class VilleController extends Controller
{
    /**
     * Lists all ville entities.
     *
     */
    public function indexAction(Request $request)
    {
        $source = new Entity('ParametreBundle:Ville');
        $grid = $this->get('grid');
        $grid->setSource($source);

        $rowAction = new RowAction('Détails', 'admin_parametre_ville_show');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Ville:show', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_parametre_ville_edit');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Ville:edit', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'admin_parametre_ville_delete');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Ville:delete', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        return $grid->getGridResponse('ville/grid.html.twig');
    }

    /**
     * Creates a new ville entity.
     *
     */
    public function newAction(Request $request)
    {
        $ville = new Ville();
        $form = $this->createForm('PS\ParametreBundle\Form\VilleType', $ville, array(
            'action' => $this->generateUrl('admin_parametre_ville_new'),
            'method' => 'POST',
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ville);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_ville_index');
        }

        return $this->render('ville/new.html.twig', array(
            'ville' => $ville,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ville entity.
     *
     */
    public function showAction(Ville $ville)
    {
        $showForm = $this->createForm('PS\ParametreBundle\Form\VilleType', $ville);

        $deleteForm = $this->createDeleteForm($ville);

        return $this->render('ville/show.html.twig', array(
            'ville' => $ville,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ville entity.
     *
     */
    public function editAction(Request $request, Ville $ville)
    {
        $deleteForm = $this->createDeleteForm($ville);
        $editForm = $this->createForm('PS\ParametreBundle\Form\VilleType', $ville, array(
            'action' => $this->generateUrl('admin_parametre_ville_edit', array('id' => $ville->getId())),
            'method' => 'POST',
        ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_parametre_ville_index');
        }

        return $this->render('ville/edit.html.twig', array(
            'ville' => $ville,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ville entity.
     *
     */
    public function deleteAction(Request $request, Ville $ville)
    {
        $form = $this->createDeleteForm($ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ville);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_ville_index');
        }


        return $this->render('ville/delete.html.twig', ['form' => $form->createView(), 'ville' => $ville]);
    }

    /**
     * Creates a form to delete a ville entity.
     *
     * @param Ville $ville The ville entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Ville $ville)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametre_ville_delete', array('id' => $ville->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
