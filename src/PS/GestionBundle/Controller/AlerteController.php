<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Entity\Alerte;
use Symfony\Component\HttpFoundation\Request;
use PS\GestionBundle\Form\AlerteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Alerte controller.
 *
 * @Route("admin/patient/alerte")
 */
class AlerteController extends Controller
{



    /**
     * Lists all alerte entities.
     *
     * @Route("/", name="gestion_alerte_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $patient = $this->getPatient();
        $source = new Entity(Alerte::class);

        $params = [];

        $grid = $this->get('grid');

        $source->manipulateQuery(function ($qb) use($patient) {
            return $qb->andWhere('_a.patient = :patient')->setParameter('patient', $patient);
        });

        $grid->setSource($source);

        $grid->setRouteUrl($this->generateUrl('gestion_alerte_index'));


        $rowAction = new RowAction('Détails', 'gestion_alerte_show');
       
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'gestion_alerte_edit');
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Suivi', 'gestion_suivialerte_index');
        $rowAction->setAttributes(['ajax' => false]);
        $grid->addRowAction($rowAction);

            /*$rowAction = new RowAction('Supprimer', 'gestion_alerte_delete');
        $rowAction->setAttributes(['data-target' => '#stack2']);
        $grid->addRowAction($rowAction);*/
    


        return $grid->getGridResponse('gestion/alerte/index.html.twig');
    }



    /**
     * Creates a new alerte entity.
     *
     * @Route("/new", name="gestion_alerte_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $alerte = new Alerte();
        $alerte->setPatient($this->getPatient());
        $form = $this->createForm(AlerteType::class, $alerte, [
                'action' => $this->generateUrl('gestion_alerte_new'),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);

         

        
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'gestion_alerte_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($alerte);
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


        return $this->render('gestion/alerte/new.html.twig', [
            'alerte' => $alerte,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a alerte entity.
     *
     * @Route("/{id}/show", name="gestion_alerte_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Alerte $alerte)
    {
            $deleteForm = $this->createDeleteForm($alerte);
        $showForm = $this->createForm(AlerteType::class, $alerte);
    $showForm->handleRequest($request);


    return $this->render('gestion/alerte/show.html.twig', [
            'alerte' => $alerte,
            'show_form' => $showForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ]);
    }

    public function getPatient($patient = null)
    {
        $_patient = $this->getUser()->getPatient();
       return $patient && $_patient->isParentOf($patient) ? $patient: $_patient; 
    }

     /**
     * Displays a form to edit an existing alerte entity.
     *
     * @Route("/data", name="gestion_alerte_data", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function data(Request $request)
    {
        $data = $this->getDoctrine()->getManager()->getRepository(Alerte::class)->findAllPatient($this->getPatient());
        return new JsonResponse([]);
    }

    /**
     * Displays a form to edit an existing alerte entity.
     *
     * @Route("/{id}/edit", name="gestion_alerte_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Alerte $alerte)
    {
        //$deleteForm = $this->createDeleteForm($alerte);
        $form = $this->createForm(AlerteType::class, $alerte, [
                'action' => $this->generateUrl('gestion_alerte_edit', ['id' => $alerte->getId()]),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);
       
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'gestion_alerte_index';
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

        return $this->render('gestion/alerte/edit.html.twig', [
            'alerte' => $alerte,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a alerte entity.
     *
     * @Route("/{id}/delete", name="gestion_alerte_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Alerte $alerte)
    {
        $form = $this->createDeleteForm($alerte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($alerte);
            $em->flush();

            $redirect = $this->generateUrl('gestion_alerte_index');

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

        return $this->render('gestion/alerte/delete.html.twig', [
            'alerte' => $alerte,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a alerte entity.
     *
     * @param Alerte $alerte The alerte entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Alerte $alerte)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'gestion_alerte_delete'
                ,   [
                        'id' => $alerte->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
