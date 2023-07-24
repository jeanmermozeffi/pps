<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Service\RowAction;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Entity\Commande;
use PS\GestionBundle\Entity\Livraison;
use PS\GestionBundle\Form\CommandeType;
use PS\GestionBundle\Form\StatutCommandeType;
use PS\GestionBundle\Form\LivraisonType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommandeController extends Controller
{
    /**
     * Lists all region entities.
     *
     */
    public function indexAction(Request $request)
    {
        $source = new Entity('GestionBundle:Commande');

        $grid = $this->get('grid');

        $grid->setSource($source);

        $rowAction = new RowAction('Détails', 'admin_gestion_commande_show');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'GestionBundle:Commande:show', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_gestion_commande_edit');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'GestionBundle:Commande:edit', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);


        /*$rowAction = new RowAction('Statut', 'admin_gestion_commande_statut');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'GestionBundle:Commande:statut', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);*/


        $rowAction = new RowAction('Statut', 'admin_gestion_commande_livraison');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'GestionBundle:Commande:livraison', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'admin_gestion_commande_delete');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'GestionBundle:Commande:delete', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        return $grid->getGridResponse('GestionBundle:Commande:grid.html.twig');
    }

    /**
     * Creates a new commande entity.
     *
     */
    public function newAction(Request $request)
    {
        $commande = new Commande();
        $form     = $this->createForm(CommandeType::class, $commande, [
            'action'            => $this->generateUrl('admin_gestion_commande_new'),
            'method'            => 'POST',
            'validation_groups' => ['create'],
        ]);

        if ($request->isMethod('POST')) {
            $commande->setStatutCommande(-1);
            $commande->setDateCommande(new \DateTime());
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();

            return $this->redirectToRoute('admin_gestion_commande_index');
        }

        return $this->render('GestionBundle:Commande:new.html.twig', [
            'commande' => $commande,
            'form'     => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Commande $commande
     * @return mixed
     */
    public function deleteAction(Request $request, Commande $commande)
    {
        $form = $this->createDeleteForm($commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($commande);
            $em->flush();

            return $this->redirectToRoute('admin_gestion_commande_index');
        }

        return $this->render('GestionBundle:Commande:delete.html.twig', ['form' => $form->createView(), 'commande' => $commande]);
    }

    /**
     * Creates a new commande entity.
     *
     */
    public function editAction(Request $request, Commande $commande)
    {
        $form     = $this->createForm(CommandeType::class, $commande, [
            'action'            => $this->generateUrl('admin_gestion_commande_edit', ['id' => $commande->getId()]),
            'method'            => 'POST',
            'validation_groups' => ['create'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_gestion_commande_index');
        }

        return $this->render('GestionBundle:Commande:edit.html.twig', [
            'commande'  => $commande,
            'edit_form' => $form->createView(),
        ]);
    }

    /**
     * Creates a new commande entity.
     *
     */
    public function statutAction(Request $request, Commande $commande)
    {
        $em = $this->getDoctrine()->getManager();
        $form     = $this->createForm(StatutCommandeType::class, $commande, [
            'action'            => $this->generateUrl('admin_gestion_commande_statut', ['id' => $commande->getId()]),
            'method'            => 'POST',
            //'validation_groups' => ['statut'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            return $this->redirectToRoute('admin_gestion_commande_index');
        }

        return $this->render('GestionBundle:Commande:statut.html.twig', [
            'commande'  => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a commande entity.
     *
     */
    public function showAction(Commande $commande)
    {
        return $this->render('GestionBundle:Commande:show.html.twig', [
            'commande' => $commande,
        ]);
    }


    public function livraisonAction(Request $request, Commande $commande)
    {
        $livraison = new Livraison();
        $form     = $this->createForm(LivraisonType::class, $livraison, [
            'action'            => $this->generateUrl('admin_gestion_commande_livraison', ['id' => $commande->getId()]),
            'method'            => 'POST'
        ]);

        if ($request->isMethod('POST')) {
            $livraison->setCommande($commande);
            $livraison->setDateLivraison(new \DateTime());
        }


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($livraison);
            $em->flush();

            return $this->redirectToRoute('admin_gestion_commande_index');
        }

        return $this->render('GestionBundle:Commande:livraison.html.twig', [
            'commande'  => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a ville entity.
     *
     * @param Commande $Commande The ville entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Commande $commande)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_gestion_commande_delete', ['id' => $commande->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
