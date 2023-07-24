<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\TypeAlerte;
use Symfony\Component\HttpFoundation\Request;
use PS\ParametreBundle\Form\TypeAlerteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Typealerte controller.
 *
 * @Route("admin/parametre/typealerte")
 */
class TypeAlerteController extends Controller
{
    /**
     * Lists all typeAlerte entities.
     *
     * @Route("/", name="parametre_typealerte_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {

        $source = new Entity(TypeAlerte::class);

        $params = [];

        $grid = $this->get('grid');

        $grid->setSource($source);

        $grid->setRouteUrl($this->generateUrl('parametre_typealerte_index'));


        $rowAction = new RowAction('Détails', 'parametre_typealerte_show');
       
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'parametre_typealerte_edit');
        $grid->addRowAction($rowAction);

            /*$rowAction = new RowAction('Supprimer', 'parametre_typealerte_delete');
        $rowAction->setAttributes(['data-target' => '#stack2']);
        $grid->addRowAction($rowAction);*/
    


        return $grid->getGridResponse('parametre/typealerte/index.html.twig');
    }

    /**
     * Creates a new typeAlerte entity.
     *
     * @Route("/new", name="parametre_typealerte_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $typeAlerte = new Typealerte();
        $form = $this->createForm(TypeAlerteType::class, $typeAlerte, [
                'action' => $this->generateUrl('parametre_typealerte_new'),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);

         

        
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'parametre_typealerte_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($typeAlerte);
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


        return $this->render('parametre/typealerte/new.html.twig', [
            'typeAlerte' => $typeAlerte,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a typeAlerte entity.
     *
     * @Route("/{id}/show", name="parametre_typealerte_show")
     * @Method("GET")
     */
    public function showAction(Request $request, TypeAlerte $typeAlerte)
    {
            $deleteForm = $this->createDeleteForm($typeAlerte);
        $showForm = $this->createForm(TypeAlerteType::class, $typeAlerte);
    $showForm->handleRequest($request);


    return $this->render('parametre/typealerte/show.html.twig', [
            'typeAlerte' => $typeAlerte,
            'show_form' => $showForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ]);
    }

    /**
     * Displays a form to edit an existing typeAlerte entity.
     *
     * @Route("/{id}/edit", name="parametre_typealerte_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TypeAlerte $typeAlerte)
    {
        //$deleteForm = $this->createDeleteForm($typeAlerte);
        $form = $this->createForm(TypeAlerteType::class, $typeAlerte, [
                'action' => $this->generateUrl('parametre_typealerte_edit', ['id' => $typeAlerte->getId()]),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);
       
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'parametre_typealerte_index';
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

        return $this->render('parametre/typealerte/edit.html.twig', [
            'typeAlerte' => $typeAlerte,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a typeAlerte entity.
     *
     * @Route("/{id}/delete", name="parametre_typealerte_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, TypeAlerte $typeAlerte)
    {
        $form = $this->createDeleteForm($typeAlerte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($typeAlerte);
            $em->flush();

            $redirect = $this->generateUrl('parametre_typealerte_index');

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

        return $this->render('parametre/typealerte/delete.html.twig', [
            'typeAlerte' => $typeAlerte,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a typeAlerte entity.
     *
     * @param TypeAlerte $typeAlerte The typeAlerte entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TypeAlerte $typeAlerte)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'parametre_typealerte_delete'
                ,   [
                        'id' => $typeAlerte->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
