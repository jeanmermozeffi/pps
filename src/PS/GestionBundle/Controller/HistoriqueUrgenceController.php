<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Entity\HistoriqueUrgence;

use PS\GestionBundle\Entity\Urgence;
use Symfony\Component\HttpFoundation\Request;
use PS\GestionBundle\Form\HistoriqueUrgenceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * HistoriqueUrgence controller.
 *
 * @Route("/admin/gestion/historique-urgence")
 */
class HistoriqueUrgenceController extends Controller
{
    /**
     * Lists all historiqueUrgence entities.
     *
     * @Route("/{id}/liste", name="gestion_historiqueurgence_index")
     * @Method("GET")
     */
    public function indexAction(Request $request, Urgence $urgence)
    {
         $em = $this->getDoctrine()->getManager();
       $historiques = $em->getRepository(HistoriqueUrgence::class)->findBy(compact('urgence'), ['date' => 'DESC']);

        $historiques = $this->get('knp_paginator')->paginate(
            $historiques,
            $request->query->get('page', 1) /*page number*/,
            30/*limit per page*/
        );

        return $this->render('gestion/historiqueurgence/index.html.twig', ['historiques' => $historiques, 'urgence' => $urgence]);
    }

    /**
     * Creates a new historiqueUrgence entity.
     *
     * @Route("/{id}/new", name="gestion_historiqueurgence_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Urgence $urgence)
    {
        $historiqueUrgence = new HistoriqueUrgence();
        $historiqueUrgence->setUtilisateur($this->getUser());
        $historiqueUrgence->setDate(new \DateTime());
        $historiqueUrgence->setUrgence($urgence);
        $form = $this->createForm(HistoriqueUrgenceType::class, $historiqueUrgence, [
                'action' => $this->generateUrl('gestion_historiqueurgence_new', ['id' => $urgence->getId()]),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);

         

        
        if ($form->isSubmitted()) {
            $response = [];
            $params = ['id' => $urgence->getId()];
            $redirectRoute = 'gestion_historiqueurgence_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($historiqueUrgence);
                $em->flush();
                $message       = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);
            } else {
                $message = $this->get('app.form_error')->all($form);
                $statut = 0;
                $this->addFlash('warning', $message);
            }

            

            if ($request->isXmlHttpRequest()) {
                $response = compact('statut', 'message', 'redirect');
                return new JsonResponse($response);
            } else {
                
                if ($statut == 1) {
                    return $this->redirect($redirect);
                }
            }

        }


        return $this->render('gestion/historiqueurgence/new.html.twig', [
            'historiqueUrgence' => $historiqueUrgence,

            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a historiqueUrgence entity.
     *
     * @Route("/{id}/show", name="gestion_historiqueurgence_show")
     * @Method("GET")
     */
    public function showAction(Request $request, HistoriqueUrgence $historiqueUrgence)
    {
            $deleteForm = $this->createDeleteForm($historiqueUrgence);
        $showForm = $this->createForm(HistoriqueUrgenceType::class, $historiqueUrgence);
    $showForm->handleRequest($request);


    return $this->render('gestion/historiqueurgence/show.html.twig', [
            'historiqueUrgence' => $historiqueUrgence,
            'show_form' => $showForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ]);
    }

    /**
     * Displays a form to edit an existing historiqueUrgence entity.
     *
     * @Route("/{id}/edit", name="gestion_historiqueurgence_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, HistoriqueUrgence $historiqueUrgence)
    {
        //$deleteForm = $this->createDeleteForm($historiqueUrgence);
        $form = $this->createForm(HistoriqueUrgenceType::class, $historiqueUrgence, [
                'action' => $this->generateUrl('gestion_historiqueurgence_edit', ['id' => $historiqueUrgence->getId()]),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);
       
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'gestion_historiqueurgence_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $message       = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);
            } else {
                $message = $this->get('app.form_error')->all($form);
                $statut = 0;
                $this->addFlash('warning', $message);
            }


            if ($request->isXmlHttpRequest()) {
                $response = compact('statut', 'message', 'redirect');
                return new JsonResponse($response);
            } else {
                if ($statut == 1) {
                    return $this->redirect($redirect);
                }
            }

            
        }

        return $this->render('gestion/historiqueurgence/edit.html.twig', [
            'historiqueUrgence' => $historiqueUrgence,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a historiqueUrgence entity.
     *
     * @Route("/{id}/delete", name="gestion_historiqueurgence_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, HistoriqueUrgence $historiqueUrgence)
    {
        $form = $this->createDeleteForm($historiqueUrgence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($historiqueUrgence);
            $em->flush();

            $redirect = $this->generateUrl('gestion_historiqueurgence_index');

            $message = 'Opération effectuée avec succès';

            $response = [
                'statut'   => 1,
                'message'  => $message,
                'redirect' => $redirect,
            ];

            $this->addFlash('success', $message);

            if (!$request->isXmlHttpRequest()) {
                return $this->redirect($redirect);
            } else {
                return new JsonResponse($response);
            }
        }

        return $this->render('gestion/historiqueurgence/delete.html.twig', [
            'historiqueUrgence' => $historiqueUrgence,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a historiqueUrgence entity.
     *
     * @param HistoriqueUrgence $historiqueUrgence The historiqueUrgence entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(HistoriqueUrgence $historiqueUrgence)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'gestion_historiqueurgence_delete'
                ,   [
                        'id' => $historiqueUrgence->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
