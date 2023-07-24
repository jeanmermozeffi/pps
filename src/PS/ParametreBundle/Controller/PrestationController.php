<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\Prestation;
use Symfony\Component\HttpFoundation\Request;
use PS\ParametreBundle\Form\PrestationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Prestation controller.
 *
 * @Route("/admin/parametre/prestation")
 */
class PrestationController extends Controller
{
    /**
     * Lists all prestation entities.
     *
     * @Route("/", name="parametre_prestation_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {

        $source = new Entity(Prestation::class);

        $params = [];

        $grid = $this->get('grid');

        $grid->setSource($source);

        $grid->setRouteUrl($this->generateUrl('parametre_prestation_index'));


        $rowAction = new RowAction('Détails', 'parametre_prestation_show');

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'parametre_prestation_edit');
        $grid->addRowAction($rowAction);

        /*$rowAction = new RowAction('Supprimer', 'parametre_prestation_delete');
        $rowAction->setAttributes(['data-target' => '#stack2']);
        $grid->addRowAction($rowAction);*/



        return $grid->getGridResponse('parametre/prestation/index.html.twig');
    }

    /**
     * Creates a new prestation entity.
     *
     * @Route("/new", name="parametre_prestation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $prestation = new Prestation();
        $form = $this->createForm(PrestationType::class, $prestation, [
            'action' => $this->generateUrl('parametre_prestation_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);




        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'parametre_prestation_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($prestation);
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


        return $this->render('parametre/prestation/new.html.twig', [
            'prestation' => $prestation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a prestation entity.
     *
     * @Route("/{id}/show", name="parametre_prestation_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Prestation $prestation)
    {
        $deleteForm = $this->createDeleteForm($prestation);
        $showForm = $this->createForm(PrestationType::class, $prestation);
        $showForm->handleRequest($request);


        return $this->render('parametre/prestation/show.html.twig', [
            'prestation' => $prestation,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing prestation entity.
     *
     * @Route("/{id}/edit", name="parametre_prestation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Prestation $prestation)
    {
        //$deleteForm = $this->createDeleteForm($prestation);
        $form = $this->createForm(PrestationType::class, $prestation, [
            'action' => $this->generateUrl('parametre_prestation_edit', ['id' => $prestation->getId()]),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'parametre_prestation_index';
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

        return $this->render('parametre/prestation/edit.html.twig', [
            'prestation' => $prestation,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a prestation entity.
     *
     * @Route("/{id}/delete", name="parametre_prestation_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Prestation $prestation)
    {
        $form = $this->createDeleteForm($prestation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($prestation);
            $em->flush();

            $redirect = $this->generateUrl('parametre_prestation_index');

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

        return $this->render('parametre/prestation/delete.html.twig', [
            'prestation' => $prestation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a prestation entity.
     *
     * @param Prestation $prestation The prestation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Prestation $prestation)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'parametre_prestation_delete',
                    [
                        'id' => $prestation->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm();
    }
}
