<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Entity\LigneAlerte;
use Symfony\Component\HttpFoundation\Request;
use PS\GestionBundle\Form\LigneAlerteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Entity\Alerte;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Lignealerte controller.
 *
 * @Route("admin/patient/suivialerte")
 */
class LigneAlerteController extends Controller
{
    /**
     * Lists all ligneAlerte entities.
     *
     * @Route("/{id}", name="gestion_suivialerte_index")
     * @Method("GET")
     */
    public function indexAction(Request $request, Alerte $alerte)
    {
        return $this->render('gestion/lignealerte/index.html.twig', [
            'alerte' => $alerte
        ]);   
    }

    /**
     * Creates a new ligneAlerte entity.
     *
     * @Route("/{id}/new", name="gestion_suivialerte_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Alerte $alerte)
    {
        $ligneAlerte = new Lignealerte();
        $ligneAlerte->setAlerte($alerte);
        $form = $this->createForm(LigneAlerteType::class, $ligneAlerte, [
                'action' => $this->generateUrl('gestion_suivialerte_new', ['id' => $alerte->getId()]),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);

         
        $ajax = false;
        
        if ($form->isSubmitted()) {
            $response = [];
            $params = ['id' => $alerte->getId()];
            $redirectRoute = 'gestion_suivialerte_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
              
                $em->persist($ligneAlerte);
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
                $response = compact('statut', 'message', 'redirect', 'ajax');
                return new JsonResponse($response);
            } else {
                
                if ($statut == 1) {
                    return $this->redirect($redirect);
                }
            }

        }


        return $this->render('gestion/lignealerte/new.html.twig', [
            'ligneAlerte' => $ligneAlerte,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a ligneAlerte entity.
     *
     * @Route("/{id}/show", name="gestion_suivialerte_show")
     * @Method("GET")
     */
    public function showAction(Request $request, LigneAlerte $ligneAlerte)
    {
            $deleteForm = $this->createDeleteForm($ligneAlerte);
        $showForm = $this->createForm(LigneAlerteType::class, $ligneAlerte);
    $showForm->handleRequest($request);


    return $this->render('gestion/lignealerte/show.html.twig', [
            'ligneAlerte' => $ligneAlerte,
            'show_form' => $showForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ]);
    }

    /**
     * Displays a form to edit an existing ligneAlerte entity.
     *
     * @Route("/{id}/edit", name="gestion_suivialerte_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, LigneAlerte $ligneAlerte)
    {
        //$deleteForm = $this->createDeleteForm($ligneAlerte);
        $form = $this->createForm(LigneAlerteType::class, $ligneAlerte, [
                'action' => $this->generateUrl('gestion_suivialerte_edit', ['id' => $ligneAlerte->getId()]),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);
       
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'gestion_suivialerte_index';
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

        return $this->render('gestion/lignealerte/edit.html.twig', [
            'ligneAlerte' => $ligneAlerte,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a ligneAlerte entity.
     *
     * @Route("/{id}/delete", name="gestion_suivialerte_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, LigneAlerte $ligneAlerte)
    {
        $form = $this->createDeleteForm($ligneAlerte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ligneAlerte);
            $em->flush();

            $redirect = $this->generateUrl('gestion_suivialerte_index');

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

        return $this->render('gestion/lignealerte/delete.html.twig', [
            'ligneAlerte' => $ligneAlerte,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a ligneAlerte entity.
     *
     * @param LigneAlerte $ligneAlerte The ligneAlerte entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(LigneAlerte $ligneAlerte)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'gestion_suivialerte_delete'
                ,   [
                        'id' => $ligneAlerte->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


      /**
     * Displays a form to edit an existing alerte entity.
     *
     * @Route("/{id}/data", name="gestion_suivialerte_data", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function data(Request $request, Alerte $alerte)
    {
        $start = $request->query->get('start');
        $end = $request->query->get('end');

        $data = $this->getDoctrine()
            ->getManager()
            ->getRepository(LigneAlerte::class)->findAllPatient($this->getPatient(), $alerte, $start, $end);
        return new JsonResponse($data);
    }


    public function getPatient($patient = null)
    {
        $_patient = $this->getUser()->getPatient();
       return $patient && $_patient->isParentOf($patient) ? $patient: $_patient;
    } 
    
}
