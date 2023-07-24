<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Service\RowAction;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Entity\Corporate;
use PS\GestionBundle\Form\CorporateHopitalType;
use PS\GestionBundle\Form\CorporatePharmacieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Ville controller.
 *
 */
class CorporateController extends Controller
{
    /**
     * Lists all ville entities.
     *
     */
    public function indexAction(Request $request)
    {
        $source = new Entity('GestionBundle:Corporate');
        $grid   = $this->get('grid');

        $user = $this->getuser();
        $source->manipulateQuery(function ($qb) use ($user) {
            $corporate = $user->getPersonne()->getCorporate();
            if ($corporate) {
                $qb->andWhere('_a.id = :corporate')->setParameter('corporate', $corporate->getId());
            }
        });
        $grid->setSource($source);

        $rowAction = new RowAction('Détails', 'admin_gestion_corporate_show');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'GestionBundle:Corporate:show', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_gestion_corporate_edit');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'GestionBundle:Corporate:edit', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Hopitaux', 'admin_gestion_corporate_hopital');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'GestionBundle:Corporate:hopital', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        /*$rowAction = new RowAction('Pharmacies', 'admin_gestion_corporate_pharmacie');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'GestionBundle:Corporate:pharmacie', 'parameters' => ['id' => $row->getField('id')]];
        });*/
        //$grid->addRowAction($rowAction);
        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $rowAction = new RowAction('Supprimer', 'admin_gestion_corporate_delete');
            $rowAction->manipulateRender(function ($action, $row) {
                return ['controller' => 'GestionBundle:Corporate:delete', 'parameters' => ['id' => $row->getField('id')]];
            });
            $grid->addRowAction($rowAction);
        }

        return $grid->getGridResponse('GestionBundle:Corporate:grid.html.twig');
    }

    /**
     * Creates a new ville entity.
     *
     */
    public function newAction(Request $request)
    {
        $corporate = new Corporate();
        $form      = $this->createForm('PS\GestionBundle\Form\CorporateType', $corporate, [
            'action' => $this->generateUrl('admin_gestion_corporate_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($corporate);
            $em->flush();

            return $this->redirectToRoute('admin_gestion_corporate_index');
        }

        return $this->render('GestionBundle:Corporate:new.html.twig', [
            'corporate' => $corporate,
            'form'      => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a ville entity.
     *
     */
    public function showAction(Corporate $corporate)
    {

        return $this->render('GestionBundle:Corporate:show.html.twig', [
            'corporate' => $corporate,
        ]);
    }

    /**
     * @param Request $request
     * @param Corporate $corporate
     * @return mixed
     */
    public function hopitalAction(Request $request, Corporate $corporate)
    {
        $oldHopitaux = $corporate->getCorporateHopitaux();
        $oldHopitaux = $oldHopitaux->toArray();
        $form        = $this->createForm(CorporateHopitalType::class, $corporate, [
            'action' => $this->generateUrl('admin_gestion_corporate_hopital', ['id' => $corporate->getId()]),
        ]);

        $form->handleRequest($request);

        foreach ($oldHopitaux as $hopital) {
            $corporate->removeCorporateHopital($hopital);
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Opération effectuée avec succès');

            return $this->redirectToRoute('admin_gestion_corporate_index');
        }
        return $this->render('GestionBundle:Corporate:hopital.html.twig', [
            'corporate' => $corporate,
            'form'      => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Corporate $corporate
     * @return mixed
     */
    public function pharmacieAction(Request $request, Corporate $corporate)
    {
        $oldPharmacies = $corporate->getCorporatePharmacies();
        $oldPharmacies = $oldPharmacies->toArray();
        $form          = $this->createForm(CorporatePharmacieType::class, $corporate, [
            'action' => $this->generateUrl('admin_gestion_corporate_pharmacie', ['id' => $corporate->getId()]),
        ]);
        $form->handleRequest($request);

        foreach ($oldPharmacies as $pharmacie) {
            $corporate->removeCorporatePharmacy($pharmacie);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Opération effectuée avec succès');
            return $this->redirectToRoute('admin_gestion_corporate_index');
        }

        return $this->render('GestionBundle:Corporate:pharmacie.html.twig', [
            'corporate' => $corporate,
            'form'      => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing ville entity.
     *
     */
    public function editAction(Request $request, Corporate $corporate)
    {

        $deleteForm = $this->createDeleteForm($corporate);
        $editForm   = $this->createForm('PS\GestionBundle\Form\CorporateType', $corporate, [
            'action' => $this->generateUrl('admin_gestion_corporate_edit', ['id' => $corporate->getId()]),
            'method' => 'POST',
            'corporate' => $corporate
        ]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_gestion_corporate_index');
        }

        return $this->render('GestionBundle:Corporate:edit.html.twig', [
            'corporate'   => $corporate,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a ville entity.
     *
     */
    public function deleteAction(Request $request, Corporate $corporate)
    {
        $form = $this->createDeleteForm($corporate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($corporate);
            $em->flush();

            return $this->redirectToRoute('admin_gestion_corporate_index');
        }

        return $this->render('GestionBundle:Corporate:delete.html.twig', ['form' => $form->createView(), 'corporate' => $corporate]);
    }

    /**
     * Creates a form to delete a ville entity.
     *
     * @param Corporate $corporate The ville entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Corporate $corporate)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_gestion_corporate_delete', ['id' => $corporate->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
