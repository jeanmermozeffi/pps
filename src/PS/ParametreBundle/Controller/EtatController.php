<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\Etat;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use PS\ParametreBundle\Form\EtatType;

/**
 * Etat controller.
 *
 */
class EtatController extends Controller
{
    /**
     * Lists all Etat entities.
     *
     */
    public function indexAction(Request $request)
    {
        $source = new Entity(Etat::class);

        $grid = $this->get('grid');

        $grid->setSource($source);


        $rowAction = new RowAction('Détails', 'admin_parametre_etat_show');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Etat:show', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_parametre_etat_edit');

        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Etat:edit', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'admin_parametre_etat_delete');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Etat:delete', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        return $grid->getGridResponse('etat/grid.html.twig');
    }

    /**
     * Creates a new Etat entity.
     *
     */
    public function newAction(Request $request)
    {
        $etat = new Etat();
        $form = $this->createForm(EtatType::class, $etat, array(
            'action' => $this->generateUrl('admin_parametre_etat_new'),
            'method' => 'POST',
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($etat);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_etat_index');
        }

        return $this->render('etat/new.html.twig', array(
            'etat' => $etat,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Etat entity.
     *
     */
    public function showAction(Etat $etat)
    {
        $showForm = $this->createForm(EtatType::class, $etat);

        $deleteForm = $this->createDeleteForm($etat);

        return $this->render('etat/show.html.twig', array(
            'etat' => $etat,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Etat entity.
     *
     */
    public function editAction(Request $request, Etat $etat)
    {
        $deleteForm = $this->createDeleteForm($etat);
        $editForm = $this->createForm(EtatType::class, $etat, array(
            'action' => $this->generateUrl('admin_parametre_etat_edit', array('id' => $etat->getId())),
            'method' => 'POST'
        ));

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_parametre_etat_index');
        }

        return $this->render('etat/edit.html.twig', array(
            'etat' => $etat,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Etat entity.
     *
     */
    public function deleteAction(Request $request, Etat $etat)
    {
        $form = $this->createDeleteForm($etat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($etat);
            $em->flush();
            return $this->redirectToRoute('admin_parametre_etat_index');
        }

        return $this->render('etat/delete.html.twig', ['form' => $form->createView(), 'etat' => $etat,]);
    }

    /**
     * Creates a form to delete a Etat entity.
     *
     * @param Etat $etat The Etat entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Etat $etat)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametre_etat_delete', array('id' => $etat->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
