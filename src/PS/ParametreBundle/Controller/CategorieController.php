<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\Categorie;
use Symfony\Component\HttpFoundation\Request;
use PS\ParametreBundle\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Categorie controller.
 *
 * @Route("/admin/parametre/categorie")
 */
class CategorieController extends Controller
{
    /**
     * Lists all categorie entities.
     *
     * @Route("/", name="parametre_categorie_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {

        $source = new Entity(Categorie::class);

        $params = [];

        $grid = $this->get('grid');

        $grid->setSource($source);

        $grid->setRouteUrl($this->generateUrl('parametre_categorie_index'));


        $rowAction = new RowAction('Détails', 'parametre_categorie_show');
       
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'parametre_categorie_edit');
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'parametre_categorie_delete');
        $rowAction->setAttributes(['data-target' => '#stack2']);
        $grid->addRowAction($rowAction);
    


        return $grid->getGridResponse('parametre/categorie/index.html.twig');
    }

    /**
     * Creates a new categorie entity.
     *
     * @Route("/new", name="parametre_categorie_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie, [
                'action' => $this->generateUrl('parametre_categorie_new'),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);

         

        
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'parametre_categorie_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($categorie);
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


        return $this->render('parametre/categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a categorie entity.
     *
     * @Route("/{id}/show", name="parametre_categorie_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Categorie $categorie)
    {
            $deleteForm = $this->createDeleteForm($categorie);
        $showForm = $this->createForm(CategorieType::class, $categorie);
    $showForm->handleRequest($request);


    return $this->render('parametre/categorie/show.html.twig', [
            'categorie' => $categorie,
            'show_form' => $showForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ]);
    }

    /**
     * Displays a form to edit an existing categorie entity.
     *
     * @Route("/{id}/edit", name="parametre_categorie_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Categorie $categorie)
    {
        //$deleteForm = $this->createDeleteForm($categorie);
        $form = $this->createForm(CategorieType::class, $categorie, [
                'action' => $this->generateUrl('parametre_categorie_edit', ['id' => $categorie->getId()]),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);
       
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'parametre_categorie_index';
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

        return $this->render('parametre/categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a categorie entity.
     *
     * @Route("/{id}/delete", name="parametre_categorie_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Categorie $categorie)
    {
        $form = $this->createDeleteForm($categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($categorie);
            $em->flush();

            $redirect = $this->generateUrl('parametre_categorie_index');

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

        return $this->render('parametre/categorie/delete.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a categorie entity.
     *
     * @param Categorie $categorie The categorie entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Categorie $categorie)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'parametre_categorie_delete'
                ,   [
                        'id' => $categorie->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
