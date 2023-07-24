<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Entity\LigneQuestionnaire;
use Symfony\Component\HttpFoundation\Request;
use PS\GestionBundle\Form\LigneQuestionnaireType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\HttpFoundation\RedirectResponse;
use PS\GestionBundle\Entity\Questionnaire;


/**
 * Lignequestionnaire controller.
 *
 * @Route("admin/gestion/ligne-questionnaire")
 */
class LigneQuestionnaireController extends Controller
{
    /**
     * Lists all ligneQuestionnaire entities.
     *
     * @Route("/{id}", name="gestion_lignequestionnaire_index")
     * @Method("GET")
     */
    public function indexAction(Request $request, Questionnaire $questionnaire)
    {

        $source = new Entity(LigneQuestionnaire::class);

        $params = [];

        $grid = $this->get('grid');

        $source->manipulateQuery(function ($qb) use($questionnaire) {
            return $qb->andWhere('_a.questionnaire = :questionnaire')->setParameter('questionnaire', $questionnaire);
        });

        $grid->setSource($source);

        $grid->setRouteUrl($this->generateUrl('gestion_lignequestionnaire_index', ['id' => $questionnaire->getId()]));


        $rowAction = new RowAction('Détails', 'gestion_lignequestionnaire_show');
       
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'gestion_lignequestionnaire_edit');
        $grid->addRowAction($rowAction);

            /*$rowAction = new RowAction('Supprimer', 'gestion_lignequestionnaire_delete');
        $rowAction->setAttributes(['data-target' => '#stack2']);
        $grid->addRowAction($rowAction);*/
    


        return $grid->getGridResponse('gestion/lignequestionnaire/index.html.twig', ['questionnaire' => $questionnaire]);
    }

    /**
     * Creates a new ligneQuestionnaire entity.
     *
     * @Route("/new/{id}", name="gestion_lignequestionnaire_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Questionnaire $questionnaire)
    {
        $ligneQuestionnaire = new Lignequestionnaire();
        $ligneQuestionnaire->setQuestionnaire($questionnaire);
        $form = $this->createForm(LigneQuestionnaireType::class, $ligneQuestionnaire, [
                'action' => $this->generateUrl('gestion_lignequestionnaire_new', ['id' => $questionnaire->getId()]),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);

         

        
        if ($form->isSubmitted()) {
            $response = [];
            $params = ['id' => $questionnaire->getId()];
            $redirectRoute = 'gestion_lignequestionnaire_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($ligneQuestionnaire);
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


        return $this->render('gestion/lignequestionnaire/new.html.twig', [
            'ligneQuestionnaire' => $ligneQuestionnaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a ligneQuestionnaire entity.
     *
     * @Route("/{id}/show", name="gestion_lignequestionnaire_show")
     * @Method("GET")
     */
    public function showAction(Request $request, LigneQuestionnaire $ligneQuestionnaire)
    {
            $deleteForm = $this->createDeleteForm($ligneQuestionnaire);
        $showForm = $this->createForm(LigneQuestionnaireType::class, $ligneQuestionnaire);
    $showForm->handleRequest($request);


    return $this->render('gestion/lignequestionnaire/show.html.twig', [
            'ligneQuestionnaire' => $ligneQuestionnaire,
            'show_form' => $showForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ]);
    }

    /**
     * Displays a form to edit an existing ligneQuestionnaire entity.
     *
     * @Route("/{id}/edit", name="gestion_lignequestionnaire_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, LigneQuestionnaire $ligneQuestionnaire)
    {
        //$deleteForm = $this->createDeleteForm($ligneQuestionnaire);
        $form = $this->createForm(LigneQuestionnaireType::class, $ligneQuestionnaire, [
                'action' => $this->generateUrl('gestion_lignequestionnaire_edit', ['id' => $ligneQuestionnaire->getId()]),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);
       
        if ($form->isSubmitted()) {
            $response = [];
           $params = ['id' => $ligneQuestionnaire->getQuestionnaire()->getId()];
            $redirectRoute = 'gestion_lignequestionnaire_index';
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

        return $this->render('gestion/lignequestionnaire/edit.html.twig', [
            'ligneQuestionnaire' => $ligneQuestionnaire,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a ligneQuestionnaire entity.
     *
     * @Route("/{id}/delete", name="gestion_lignequestionnaire_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, LigneQuestionnaire $ligneQuestionnaire)
    {
        $form = $this->createDeleteForm($ligneQuestionnaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ligneQuestionnaire);
            $em->flush();

            $redirect = $this->generateUrl('gestion_lignequestionnaire_index');

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

        return $this->render('gestion/lignequestionnaire/delete.html.twig', [
            'ligneQuestionnaire' => $ligneQuestionnaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a ligneQuestionnaire entity.
     *
     * @param LigneQuestionnaire $ligneQuestionnaire The ligneQuestionnaire entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(LigneQuestionnaire $ligneQuestionnaire)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'gestion_lignequestionnaire_delete'
                ,   [
                        'id' => $ligneQuestionnaire->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
