<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\ListeExamen;
use Symfony\Component\HttpFoundation\Request;
use PS\ParametreBundle\Form\ListeExamenType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Listeexaman controller.
 *
 * @Route("admin/parametre/listeexamen")
 */
class ListeExamenController extends Controller
{
    /**
     * Lists all listeExamen entities.
     *
     * @Route("/", name="parametre_listeexamen_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {

        $source = new Entity(ListeExamen::class);

        $params = [];

        $grid = $this->get('grid');

        $grid->setSource($source);

        $grid->setRouteUrl($this->generateUrl('parametre_listeexamen_index'));


        $rowAction = new RowAction('Détails', 'parametre_listeexamen_show');

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'parametre_listeexamen_edit');
        $grid->addRowAction($rowAction);

        /*$rowAction = new RowAction('Supprimer', 'parametre_listeexamen_delete');
        $rowAction->setAttributes(['data-target' => '#stack2']);
        $grid->addRowAction($rowAction);*/



        return $grid->getGridResponse('parametre/listeexamen/index.html.twig');
    }

    /**
     * Creates a new listeExamen entity.
     *
     * @Route("/new", name="parametre_listeexamen_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $listeExamen = new ListeExamen();
        $form = $this->createForm(ListeExamenType::class, $listeExamen, [
            'action' => $this->generateUrl('parametre_listeexamen_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);




        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'parametre_listeexamen_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($listeExamen);
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


        return $this->render('parametre/listeexamen/new.html.twig', [
            'listeExamen' => $listeExamen,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a listeExamen entity.
     *
     * @Route("/{id}/show", name="parametre_listeexamen_show")
     * @Method("GET")
     */
    public function showAction(Request $request, ListeExamen $listeExamen)
    {
        $deleteForm = $this->createDeleteForm($listeExamen);
        $showForm = $this->createForm(ListeExamenType::class, $listeExamen);
        $showForm->handleRequest($request);


        return $this->render('parametre/listeexamen/show.html.twig', [
            'listeExamen' => $listeExamen,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing listeExamen entity.
     *
     * @Route("/{id}/edit", name="parametre_listeexamen_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ListeExamen $listeExamen)
    {
        //$deleteForm = $this->createDeleteForm($listeExamen);
        $form = $this->createForm(ListeExamenType::class, $listeExamen, [
            'action' => $this->generateUrl('parametre_listeexamen_edit', ['id' => $listeExamen->getId()]),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'parametre_listeexamen_index';
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

        return $this->render('parametre/listeexamen/edit.html.twig', [
            'listeExamen' => $listeExamen,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a listeExamen entity.
     *
     * @Route("/{id}/delete", name="parametre_listeexamen_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, ListeExamen $listeExamen)
    {
        $form = $this->createDeleteForm($listeExamen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($listeExamen);
            $em->flush();

            $redirect = $this->generateUrl('parametre_listeexamen_index');

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

        return $this->render('parametre/listeexamen/delete.html.twig', [
            'listeExamen' => $listeExamen,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a listeExamen entity.
     *
     * @param ListeExamen $listeExamen The listeExamen entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ListeExamen $listeExamen)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'parametre_listeexamen_delete',
                    [
                        'id' => $listeExamen->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm();
    }
}
