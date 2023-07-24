<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\Statut;
use Symfony\Component\HttpFoundation\Request;
use PS\ParametreBundle\Form\StatutType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Statut controller.
 *
 * @Route("/admin/parametre/statut")
 */
class StatutController extends Controller
{
    /**
     * Lists all statut entities.
     *
     * @Route("/", name="parametre_statut_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {

        $source = new Entity(Statut::class);

        $params = [];

        $grid = $this->get('grid');

        $grid->setSource($source);

        $grid->setRouteUrl($this->generateUrl('parametre_statut_index'));


        $rowAction = new RowAction('Détails', 'parametre_statut_show');

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'parametre_statut_edit');
        $grid->addRowAction($rowAction);

        /*$rowAction = new RowAction('Supprimer', 'parametre_statut_delete');
        $rowAction->setAttributes(['data-target' => '#stack2']);
        $grid->addRowAction($rowAction);*/



        return $grid->getGridResponse('parametre/statut/index.html.twig');
    }

    /**
     * Creates a new statut entity.
     *
     * @Route("/new", name="parametre_statut_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $statut = new Statut();
        $form = $this->createForm(StatutType::class, $statut, [
            'action' => $this->generateUrl('parametre_statut_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);




        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'parametre_statut_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($statut);
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


        return $this->render('parametre/statut/new.html.twig', [
            'statut' => $statut,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a statut entity.
     *
     * @Route("/{id}/show", name="parametre_statut_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Statut $statut)
    {
        $deleteForm = $this->createDeleteForm($statut);
        $showForm = $this->createForm(StatutType::class, $statut);
        $showForm->handleRequest($request);


        return $this->render('parametre/statut/show.html.twig', [
            'statut' => $statut,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing statut entity.
     *
     * @Route("/{id}/edit", name="parametre_statut_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Statut $statut)
    {
        //$deleteForm = $this->createDeleteForm($statut);
        $form = $this->createForm(StatutType::class, $statut, [
            'action' => $this->generateUrl('parametre_statut_edit', ['id' => $statut->getId()]),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'parametre_statut_index';
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

        return $this->render('parametre/statut/edit.html.twig', [
            'statut' => $statut,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a statut entity.
     *
     * @Route("/{id}/delete", name="parametre_statut_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Statut $statut)
    {
        $form = $this->createDeleteForm($statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($statut);
            $em->flush();

            $redirect = $this->generateUrl('parametre_statut_index');

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

        return $this->render('parametre/statut/delete.html.twig', [
            'statut' => $statut,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a statut entity.
     *
     * @param Statut $statut The statut entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Statut $statut)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'parametre_statut_delete',
                    [
                        'id' => $statut->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm();
    }
}
