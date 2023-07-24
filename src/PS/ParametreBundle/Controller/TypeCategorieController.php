<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\TypeCategorie;
use Symfony\Component\HttpFoundation\Request;
use PS\ParametreBundle\Form\TypeCategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Typecategorie controller.
 *
 * @Route("/admin/parametre/type-categorie")
 */
class TypeCategorieController extends Controller
{
    /**
     * Lists all typeCategorie entities.
     *
     * @Route("/", name="parametre_typecategorie_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {

        $source = new Entity(TypeCategorie::class);

        $params = [];

        $grid = $this->get('grid');

        $grid->setSource($source);

        $grid->setRouteUrl($this->generateUrl('parametre_typecategorie_index'));


        $rowAction = new RowAction('Détails', 'parametre_typecategorie_show');
       
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'parametre_typecategorie_edit');
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'parametre_typecategorie_delete');
        $rowAction->setAttributes(['data-target' => '#stack2']);
        $grid->addRowAction($rowAction);
    


        return $grid->getGridResponse('parametre/typecategorie/index.html.twig');
    }

    /**
     * Creates a new typeCategorie entity.
     *
     * @Route("/new", name="parametre_typecategorie_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $typeCategorie = new Typecategorie();
        $form = $this->createForm(TypeCategorieType::class, $typeCategorie, [
                'action' => $this->generateUrl('parametre_typecategorie_new'),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);

         

        
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'parametre_typecategorie_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($typeCategorie);
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


        return $this->render('parametre/typecategorie/new.html.twig', [
            'typeCategorie' => $typeCategorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a typeCategorie entity.
     *
     * @Route("/{id}/show", name="parametre_typecategorie_show")
     * @Method("GET")
     */
    public function showAction(Request $request, TypeCategorie $typeCategorie)
    {
            $deleteForm = $this->createDeleteForm($typeCategorie);
        $showForm = $this->createForm(TypeCategorieType::class, $typeCategorie);
    $showForm->handleRequest($request);


    return $this->render('parametre/typecategorie/show.html.twig', [
            'typeCategorie' => $typeCategorie,
            'show_form' => $showForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ]);
    }

    /**
     * Displays a form to edit an existing typeCategorie entity.
     *
     * @Route("/{id}/edit", name="parametre_typecategorie_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TypeCategorie $typeCategorie)
    {
        //$deleteForm = $this->createDeleteForm($typeCategorie);
        $form = $this->createForm(TypeCategorieType::class, $typeCategorie, [
                'action' => $this->generateUrl('parametre_typecategorie_edit', ['id' => $typeCategorie->getId()]),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);
       
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'parametre_typecategorie_index';
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

        return $this->render('parametre/typecategorie/edit.html.twig', [
            'typeCategorie' => $typeCategorie,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a typeCategorie entity.
     *
     * @Route("/{id}/delete", name="parametre_typecategorie_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, TypeCategorie $typeCategorie)
    {
        $form = $this->createDeleteForm($typeCategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($typeCategorie);
            $em->flush();

            $redirect = $this->generateUrl('parametre_typecategorie_index');

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

        return $this->render('parametre/typecategorie/delete.html.twig', [
            'typeCategorie' => $typeCategorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a typeCategorie entity.
     *
     * @param TypeCategorie $typeCategorie The typeCategorie entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TypeCategorie $typeCategorie)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'parametre_typecategorie_delete'
                ,   [
                        'id' => $typeCategorie->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
