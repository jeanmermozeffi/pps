<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Entity\Urgence;
use Symfony\Component\HttpFoundation\Request;
use PS\GestionBundle\Form\UrgenceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Urgence controller.
 *
 * @Route("/admin/gestion/urgence")
 */
class UrgenceController extends Controller
{
    /**
     * Lists all urgence entities.
     *
     * @Route("/", name="gestion_urgence_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
         

       $em       = $this->getDoctrine()->getManager();
       $user = $this->getUser();
       $repUrgence = $em->getRepository(Urgence::class);
       if ($this->isGranted('ROLE_URGENTISTE')) {
         $urgences = $repUrgence->findAll();
       } else {
        $urgences = $repUrgence->findByUtilisateur($user);
       }
        

        $urgences = $this->get('knp_paginator')->paginate(
            $urgences,
            $request->query->get('page', 1) /*page number*/,
            30/*limit per page*/
        );

        return $this->render('gestion/urgence/index.html.twig', ['urgences' => $urgences]);
    }

    /**
     * Creates a new urgence entity.
     *
     * @Route("/new", name="gestion_urgence_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $urgence = new Urgence();
        $user = $this->getUser();
        $urgence->setUtilisateur( $user );
         if ($this->isGranted('ROLE_CUSTOMER')) {
         $urgence->setContact( $user->getPersonne()->getContact() );
     }
         $urgence->setDate( new \DateTime() );
        $form = $this->createForm(UrgenceType::class, $urgence, [
                'action' => $this->generateUrl('gestion_urgence_new'),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);

         

        
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'gestion_urgence_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($urgence);
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


        return $this->render('gestion/urgence/new.html.twig', [
            'urgence' => $urgence,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a urgence entity.
     *
     * @Route("/{id}/show", name="gestion_urgence_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Urgence $urgence)
    {
            $deleteForm = $this->createDeleteForm($urgence);
        $showForm = $this->createForm(UrgenceType::class, $urgence);
    $showForm->handleRequest($request);


    return $this->render('gestion/urgence/show.html.twig', [
            'urgence' => $urgence,
            'show_form' => $showForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ]);
    }

    /**
     * Displays a form to edit an existing urgence entity.
     *
     * @Route("/{id}/edit", name="gestion_urgence_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Urgence $urgence)
    {
        //$deleteForm = $this->createDeleteForm($urgence);
        $form = $this->createForm(UrgenceType::class, $urgence, [
                'action' => $this->generateUrl('gestion_urgence_edit', ['id' => $urgence->getId()]),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);
       
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'gestion_urgence_index';
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

        return $this->render('gestion/urgence/edit.html.twig', [
            'urgence' => $urgence,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a urgence entity.
     *
     * @Route("/{id}/delete", name="gestion_urgence_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Urgence $urgence)
    {
        $form = $this->createDeleteForm($urgence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($urgence);
            $em->flush();

            $redirect = $this->generateUrl('gestion_urgence_index');

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

        return $this->render('gestion/urgence/delete.html.twig', [
            'urgence' => $urgence,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a urgence entity.
     *
     * @param Urgence $urgence The urgence entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Urgence $urgence)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'gestion_urgence_delete'
                ,   [
                        'id' => $urgence->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
