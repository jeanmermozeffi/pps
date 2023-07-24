<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\TypeStatut;
use Symfony\Component\HttpFoundation\Request;
use PS\ParametreBundle\Form\TypeStatutType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Typestatut controller.
 *
 * @Route("/admin/parametre/typestatut")
 */
class TypeStatutController extends Controller
{
    /**
     * Lists all typeStatut entities.
     *
     * @Route("/", name="parametre_typestatut_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {

        $source = new Entity(TypeStatut::class);

        $params = [];

        $grid = $this->get('grid');

        $grid->setSource($source);

        $grid->setRouteUrl($this->generateUrl('parametre_typestatut_index'));


        $rowAction = new RowAction('Détails', 'parametre_typestatut_show');

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'parametre_typestatut_edit');
        $grid->addRowAction($rowAction);

        /*$rowAction = new RowAction('Supprimer', 'parametre_typestatut_delete');
        $rowAction->setAttributes(['data-target' => '#stack2']);
        $grid->addRowAction($rowAction);*/



        return $grid->getGridResponse('parametre/typestatut/index.html.twig');
    }

    /**
     * Creates a new typeStatut entity.
     *
     * @Route("/new", name="parametre_typestatut_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $typeStatut = new Typestatut();
        $form = $this->createForm(TypeStatutType::class, $typeStatut, [
            'action' => $this->generateUrl('parametre_typestatut_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);




        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'parametre_typestatut_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($typeStatut);
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


        return $this->render('parametre/typestatut/new.html.twig', [
            'typeStatut' => $typeStatut,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a typeStatut entity.
     *
     * @Route("/{id}/show", name="parametre_typestatut_show")
     * @Method("GET")
     */
    public function showAction(Request $request, TypeStatut $typeStatut)
    {
        $deleteForm = $this->createDeleteForm($typeStatut);
        $showForm = $this->createForm(TypeStatutType::class, $typeStatut);
        $showForm->handleRequest($request);


        return $this->render('parametre/typestatut/show.html.twig', [
            'typeStatut' => $typeStatut,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing typeStatut entity.
     *
     * @Route("/{id}/edit", name="parametre_typestatut_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TypeStatut $typeStatut)
    {
        //$deleteForm = $this->createDeleteForm($typeStatut);
        $form = $this->createForm(TypeStatutType::class, $typeStatut, [
            'action' => $this->generateUrl('parametre_typestatut_edit', ['id' => $typeStatut->getId()]),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'parametre_typestatut_index';
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

        return $this->render('parametre/typestatut/edit.html.twig', [
            'typeStatut' => $typeStatut,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a typeStatut entity.
     *
     * @Route("/{id}/delete", name="parametre_typestatut_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, TypeStatut $typeStatut)
    {
        $form = $this->createDeleteForm($typeStatut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($typeStatut);
            $em->flush();

            $redirect = $this->generateUrl('parametre_typestatut_index');

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

        return $this->render('parametre/typestatut/delete.html.twig', [
            'typeStatut' => $typeStatut,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a typeStatut entity.
     *
     * @param TypeStatut $typeStatut The typeStatut entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TypeStatut $typeStatut)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'parametre_typestatut_delete',
                    [
                        'id' => $typeStatut->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm();
    }
}
