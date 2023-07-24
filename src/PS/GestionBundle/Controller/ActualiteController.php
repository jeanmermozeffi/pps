<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Entity\Actualite;
use Symfony\Component\HttpFoundation\Request;
use PS\GestionBundle\Form\ActualiteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Actualite controller.
 *
 * @Route("admin/gestion/actualite")
 */
class ActualiteController extends Controller
{
    /**
     * Lists all actualite entities.
     *
     * @Route("/", name="gestion_actualite_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {

        $source = new Entity(Actualite::class);

        $params = [];

        $grid = $this->get('grid');

        $grid->setSource($source);

        $grid->setRouteUrl($this->generateUrl('gestion_actualite_index'));


      

        $rowAction = new RowAction('Modifier', 'gestion_actualite_edit');
        $rowAction->setAttributes(['ajax' => false]);
        $grid->addRowAction($rowAction);

            /*$rowAction = new RowAction('Supprimer', 'gestion_actualite_delete');
        $rowAction->setAttributes(['data-target' => '#stack2']);
        $grid->addRowAction($rowAction);*/
    


        return $grid->getGridResponse('gestion/actualite/index.html.twig');
    }

    /**
     * Creates a new actualite entity.
     *
     * @Route("/new", name="gestion_actualite_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $actualite = new Actualite();
        $form = $this->createForm(ActualiteType::class, $actualite, [
                'action' => $this->generateUrl('gestion_actualite_new'),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);

         $ajax = false;

        
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'gestion_actualite_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($actualite);
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
                $response = compact('statut', 'message', 'redirect', 'ajax');
                return new JsonResponse($response);
            } else {
                
                if ($statut == 1) {
                    return $this->redirect($redirect);
                }
            }

        }


        return $this->render('gestion/actualite/new.html.twig', [
            'actualite' => $actualite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a actualite entity.
     *
     * @Route("/{id}/show", name="gestion_actualite_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Actualite $actualite)
    {
            $deleteForm = $this->createDeleteForm($actualite);
        $showForm = $this->createForm(ActualiteType::class, $actualite);
    $showForm->handleRequest($request);


    return $this->render('gestion/actualite/show.html.twig', [
            'actualite' => $actualite,
            'show_form' => $showForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ]);
    }

    /**
     * Displays a form to edit an existing actualite entity.
     *
     * @Route("/{id}/edit", name="gestion_actualite_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Actualite $actualite)
    {
        //$deleteForm = $this->createDeleteForm($actualite);
        $form = $this->createForm(ActualiteType::class, $actualite, [
                'action' => $this->generateUrl('gestion_actualite_edit', ['id' => $actualite->getId()]),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);

        $ajax = false;
       
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'gestion_actualite_index';
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
                $response = compact('statut', 'message', 'redirect','ajax');
                return new JsonResponse($response);
            } else {
                if ($statut == 1) {
                    return $this->redirect($redirect);
                }
            }

            
        }

        return $this->render('gestion/actualite/edit.html.twig', [
            'actualite' => $actualite,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a actualite entity.
     *
     * @Route("/{id}/delete", name="gestion_actualite_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Actualite $actualite)
    {
        $form = $this->createDeleteForm($actualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($actualite);
            $em->flush();

            $redirect = $this->generateUrl('gestion_actualite_index');

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

        return $this->render('gestion/actualite/delete.html.twig', [
            'actualite' => $actualite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a actualite entity.
     *
     * @param Actualite $actualite The actualite entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Actualite $actualite)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'gestion_actualite_delete'
                ,   [
                        'id' => $actualite->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
