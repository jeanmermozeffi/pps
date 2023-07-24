<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Entity\PassClient;
use Symfony\Component\HttpFoundation\Request;
use PS\GestionBundle\Form\PassClientType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Passclient controller.
 *
 * @Route("/admin/gestion/pass-client")
 */
class PassClientController extends Controller
{
    /**
     * Lists all passClient entities.
     *
     * @Route("/", name="gestion_passclient_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {

        $source = new Entity(PassClient::class);

        $params = [];

        $grid = $this->get('grid');

        $grid->setSource($source);

        $grid->setRouteUrl($this->generateUrl('gestion_passclient_index'));


        $rowAction = new RowAction('Détails', 'gestion_passclient_show');
       
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'gestion_passclient_edit');
        $grid->addRowAction($rowAction);

            /*$rowAction = new RowAction('Supprimer', 'gestion_passclient_delete');
        $rowAction->setAttributes(['data-target' => '#stack2']);
        $grid->addRowAction($rowAction);*/
    


        return $grid->getGridResponse('gestion/passclient/index.html.twig');
    }

    /**
     * Creates a new passClient entity.
     *
     * @Route("/new", name="gestion_passclient_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $passClient = new Passclient();
        $form = $this->createForm(PassClientType::class, $passClient, [
                'action' => $this->generateUrl('gestion_passclient_new'),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);

         

        
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'gestion_passclient_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($passClient);
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


        return $this->render('gestion/passclient/new.html.twig', [
            'passClient' => $passClient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a passClient entity.
     *
     * @Route("/{id}/show", name="gestion_passclient_show")
     * @Method("GET")
     */
    public function showAction(Request $request, PassClient $passClient)
    {
            $deleteForm = $this->createDeleteForm($passClient);
        $showForm = $this->createForm(PassClientType::class, $passClient);
    $showForm->handleRequest($request);


    return $this->render('gestion/passclient/show.html.twig', [
            'passClient' => $passClient,
            'show_form' => $showForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ]);
    }

    /**
     * Displays a form to edit an existing passClient entity.
     *
     * @Route("/{id}/edit", name="gestion_passclient_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PassClient $passClient)
    {
        //$deleteForm = $this->createDeleteForm($passClient);
        $form = $this->createForm(PassClientType::class, $passClient, [
                'action' => $this->generateUrl('gestion_passclient_edit', ['id' => $passClient->getId()]),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);
       
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'gestion_passclient_index';
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

        return $this->render('gestion/passclient/edit.html.twig', [
            'passClient' => $passClient,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a passClient entity.
     *
     * @Route("/{id}/delete", name="gestion_passclient_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, PassClient $passClient)
    {
        $form = $this->createDeleteForm($passClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($passClient);
            $em->flush();

            $redirect = $this->generateUrl('gestion_passclient_index');

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

        return $this->render('gestion/passclient/delete.html.twig', [
            'passClient' => $passClient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a passClient entity.
     *
     * @param PassClient $passClient The passClient entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PassClient $passClient)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'gestion_passclient_delete'
                ,   [
                        'id' => $passClient->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
