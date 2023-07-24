<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\Constante;
use Symfony\Component\HttpFoundation\Request;
use PS\ParametreBundle\Form\ConstanteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Constante controller.
 *
 * @Route("/admin/parametre/constante")
 */
class ConstanteController extends Controller
{
    /**
     * Lists all constante entities.
     *
     * @Route("/", name="parametre_constante_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {

        $source = new Entity(Constante::class);

        $params = [];

        $grid = $this->get('grid');

        $grid->setSource($source);

        $grid->setRouteUrl($this->generateUrl('parametre_constante_index'));


        $rowAction = new RowAction('Détails', 'parametre_constante_show');

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'parametre_constante_edit');
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'parametre_constante_delete');
        $rowAction->setAttributes(['data-target' => '#stack2']);
        $grid->addRowAction($rowAction);



        return $grid->getGridResponse('parametre/constante/index.html.twig');
    }

    /**
     * Creates a new constante entity.
     *
     * @Route("/new", name="parametre_constante_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $constante = new Constante();
        $form = $this->createForm(ConstanteType::class, $constante, [
            'action' => $this->generateUrl('parametre_constante_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);




        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'parametre_constante_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($constante);
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


        return $this->render('parametre/constante/new.html.twig', [
            'constante' => $constante,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a constante entity.
     *
     * @Route("/{id}/show", name="parametre_constante_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Constante $constante)
    {
        $deleteForm = $this->createDeleteForm($constante);
        $showForm = $this->createForm(ConstanteType::class, $constante);
        $showForm->handleRequest($request);


        return $this->render('parametre/constante/show.html.twig', [
            'constante' => $constante,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing constante entity.
     *
     * @Route("/{id}/edit", name="parametre_constante_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Constante $constante)
    {
        //$deleteForm = $this->createDeleteForm($constante);
        $form = $this->createForm(ConstanteType::class, $constante, [
            'action' => $this->generateUrl('parametre_constante_edit', ['id' => $constante->getId()]),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'parametre_constante_index';
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

        return $this->render('parametre/constante/edit.html.twig', [
            'constante' => $constante,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a constante entity.
     *
     * @Route("/{id}/delete", name="parametre_constante_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Constante $constante)
    {
        $form = $this->createDeleteForm($constante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($constante);
            $em->flush();

            $redirect = $this->generateUrl('parametre_constante_index');

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

        return $this->render('parametre/constante/delete.html.twig', [
            'constante' => $constante,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a constante entity.
     *
     * @param Constante $constante The constante entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Constante $constante)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'parametre_constante_delete',
                    [
                        'id' => $constante->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm();
    }
}
