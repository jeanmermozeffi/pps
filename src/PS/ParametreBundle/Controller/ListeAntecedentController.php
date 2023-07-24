<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\ListeAntecedent;
use Symfony\Component\HttpFoundation\Request;
use PS\ParametreBundle\Form\ListeAntecedentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Listeantecedent controller.
 *
 * @Route("/admin/parametre/liste-antecedent")
 */
class ListeAntecedentController extends Controller
{
    /**
     * Lists all listeAntecedent entities.
     *
     * @Route("/", name="parametre_listeantecedent_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {

        $source = new Entity(ListeAntecedent::class);

        $params = [];

        $grid = $this->get('grid');

        $grid->setSource($source);

        $grid->setRouteUrl($this->generateUrl('parametre_listeantecedent_index'));


        $rowAction = new RowAction('Détails', 'parametre_listeantecedent_show');

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'parametre_listeantecedent_edit');
        $grid->addRowAction($rowAction);

        /*$rowAction = new RowAction('Supprimer', 'parametre_listeantecedent_delete');
        $rowAction->setAttributes(['data-target' => '#stack2']);
        $grid->addRowAction($rowAction);*/



        return $grid->getGridResponse('parametre/listeantecedent/index.html.twig');
    }

    /**
     * Creates a new listeAntecedent entity.
     *
     * @Route("/new", name="parametre_listeantecedent_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $listeAntecedent = new Listeantecedent();
        $form = $this->createForm(ListeAntecedentType::class, $listeAntecedent, [
            'action' => $this->generateUrl('parametre_listeantecedent_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);




        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'parametre_listeantecedent_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($listeAntecedent);
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


        return $this->render('parametre/listeantecedent/new.html.twig', [
            'listeAntecedent' => $listeAntecedent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a listeAntecedent entity.
     *
     * @Route("/{id}/show", name="parametre_listeantecedent_show")
     * @Method("GET")
     */
    public function showAction(Request $request, ListeAntecedent $listeAntecedent)
    {
        $deleteForm = $this->createDeleteForm($listeAntecedent);
        $showForm = $this->createForm(ListeAntecedentType::class, $listeAntecedent);
        $showForm->handleRequest($request);


        return $this->render('parametre/listeantecedent/show.html.twig', [
            'listeAntecedent' => $listeAntecedent,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing listeAntecedent entity.
     *
     * @Route("/{id}/edit", name="parametre_listeantecedent_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ListeAntecedent $listeAntecedent)
    {
        //$deleteForm = $this->createDeleteForm($listeAntecedent);
        $form = $this->createForm(ListeAntecedentType::class, $listeAntecedent, [
            'action' => $this->generateUrl('parametre_listeantecedent_edit', ['id' => $listeAntecedent->getId()]),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'parametre_listeantecedent_index';
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

        return $this->render('parametre/listeantecedent/edit.html.twig', [
            'listeAntecedent' => $listeAntecedent,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a listeAntecedent entity.
     *
     * @Route("/{id}/delete", name="parametre_listeantecedent_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, ListeAntecedent $listeAntecedent)
    {
        $form = $this->createDeleteForm($listeAntecedent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($listeAntecedent);
            $em->flush();

            $redirect = $this->generateUrl('parametre_listeantecedent_index');

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

        return $this->render('parametre/listeantecedent/delete.html.twig', [
            'listeAntecedent' => $listeAntecedent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a listeAntecedent entity.
     *
     * @param ListeAntecedent $listeAntecedent The listeAntecedent entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ListeAntecedent $listeAntecedent)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'parametre_listeantecedent_delete',
                    [
                        'id' => $listeAntecedent->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm();
    }
}
