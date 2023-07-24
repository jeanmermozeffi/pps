<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\TypeHopital;
use Symfony\Component\HttpFoundation\Request;
use PS\ParametreBundle\Form\TypeHopitalType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Typehopital controller.
 *
 * @Route("/admin/parametre/type-hopital")
 */
class TypeHopitalController extends Controller
{
    /**
     * Lists all typeHopital entities.
     *
     * @Route("/", name="parametre_typehopital_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {

        $source = new Entity(TypeHopital::class);

        $params = [];

        $grid = $this->get('grid');

        $grid->setSource($source);

        $grid->setRouteUrl($this->generateUrl('parametre_typehopital_index'));


        $rowAction = new RowAction('Détails', 'parametre_typehopital_show');

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'parametre_typehopital_edit');
        $grid->addRowAction($rowAction);

        /*$rowAction = new RowAction('Supprimer', 'parametre_typehopital_delete');
        $rowAction->setAttributes(['data-target' => '#stack2']);
        $grid->addRowAction($rowAction);*/



        return $grid->getGridResponse('parametre/typehopital/index.html.twig');
    }

    /**
     * Creates a new typeHopital entity.
     *
     * @Route("/new", name="parametre_typehopital_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $typeHopital = new Typehopital();
        $form = $this->createForm(TypeHopitalType::class, $typeHopital, [
            'action' => $this->generateUrl('parametre_typehopital_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);




        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'parametre_typehopital_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($typeHopital);
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


        return $this->render('parametre/typehopital/new.html.twig', [
            'typeHopital' => $typeHopital,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a typeHopital entity.
     *
     * @Route("/{id}/show", name="parametre_typehopital_show")
     * @Method("GET")
     */
    public function showAction(Request $request, TypeHopital $typeHopital)
    {
        $deleteForm = $this->createDeleteForm($typeHopital);
        $showForm = $this->createForm(TypeHopitalType::class, $typeHopital);
        $showForm->handleRequest($request);


        return $this->render('parametre/typehopital/show.html.twig', [
            'typeHopital' => $typeHopital,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing typeHopital entity.
     *
     * @Route("/{id}/edit", name="parametre_typehopital_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TypeHopital $typeHopital)
    {
        //$deleteForm = $this->createDeleteForm($typeHopital);
        $form = $this->createForm(TypeHopitalType::class, $typeHopital, [
            'action' => $this->generateUrl('parametre_typehopital_edit', ['id' => $typeHopital->getId()]),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'parametre_typehopital_index';
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

        return $this->render('parametre/typehopital/edit.html.twig', [
            'typeHopital' => $typeHopital,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a typeHopital entity.
     *
     * @Route("/{id}/delete", name="parametre_typehopital_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, TypeHopital $typeHopital)
    {
        $form = $this->createDeleteForm($typeHopital);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($typeHopital);
            $em->flush();

            $redirect = $this->generateUrl('parametre_typehopital_index');

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

        return $this->render('parametre/typehopital/delete.html.twig', [
            'typeHopital' => $typeHopital,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a typeHopital entity.
     *
     * @param TypeHopital $typeHopital The typeHopital entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TypeHopital $typeHopital)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'parametre_typehopital_delete',
                    [
                        'id' => $typeHopital->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm();
    }
}
