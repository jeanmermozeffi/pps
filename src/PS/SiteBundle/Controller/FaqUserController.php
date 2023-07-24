<?php

namespace PS\SiteBundle\Controller;

use PS\SiteBundle\Service\RowAction;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\SiteBundle\Entity\Faq;
use PS\SiteBundle\Form\FaqType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Ville controller.
 * 
 *
 */
class FaqUserController extends Controller
{
    /**
     * Lists all ville entities.
     *
     */
    public function indexAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();

        $dql = 'SELECT a FROM GestionBundle:Faq a';
        $query = $em->createQuery($dql);

        $faqs      = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('SiteBundle:Faq:index.html.twig', ['faqs' => $faqs]);
    }

    /**
     * Creates a new ville entity.
     *
     */
    /*  public function newAction(Request $request)
    {
        $faq = new Faq();
        $form      = $this->createForm(FaqType::class, $faq, [
            'action' => $this->generateUrl('admin_gestion_faq_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $faq->setDateAjoutFaq(new \DateTime());
            $faq->setDateModifFaq(new \DateTime());
            $em->persist($faq);
            $em->flush();
            $this->addFlash('message', 'Opération avec succès');
            return $this->redirectToRoute('admin_gestion_faq_index');
        }

        return $this->render('SiteBundle:Faq:new.html.twig', [
            'faq' => $faq,
            'form'      => $form->createView(),
        ]);
    }*/

    /**
     * Finds and displays a ville entity.
     *
     */
    /*public function showAction(Faq $faq)
    {

        return $this->render('SiteBundle:Faq:show.html.twig', [
            'faq' => $faq,
        ]);
    }



    /**
     * Displays a form to edit an existing ville entity.
     *
     */
    /*  public function editAction(Request $request, Faq $faq)
    {

        $faq->setDateModifFaq(new \DateTime());

        $editForm   = $this->createForm(FaqType::class, $faq, [
            'action' => $this->generateUrl('admin_gestion_faq_edit', ['id' => $faq->getId()]),
            'method' => 'POST',
        ]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('message', 'Opération avec succès');
            return $this->redirectToRoute('admin_gestion_faq_index');
        }

        return $this->render('SiteBundle:Faq:edit.html.twig', [
            'faq'   => $faq,
            'edit_form'   => $editForm->createView(),

        ]);
    }

    /**
     * Deletes a ville entity.
     *
     */
    /*  public function deleteAction(Request $request, Faq $faq)
    {
        $form = $this->createDeleteForm($faq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($faq);
            $em->flush();

            $this->addFlash('message', 'Opération avec succès');

            return $this->redirectToRoute('admin_gestion_faq_index');
        }

        return $this->render('SiteBundle:Faq:delete.html.twig', ['form' => $form->createView(), 'faq' => $faq]);
    }

    /**
     * Creates a form to delete a ville entity.
     *
     * @param Faq $faq The ville entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    /*  private function createDeleteForm(Faq $faq)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_gestion_faq_delete', ['id' => $faq->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }*/
}
