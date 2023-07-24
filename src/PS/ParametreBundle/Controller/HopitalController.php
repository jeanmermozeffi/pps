<?php

namespace PS\ParametreBundle\Controller;

use PS\GestionBundle\Service\RowAction;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\ParametreBundle\Entity\Hopital;
use PS\GestionBundle\Entity\CorporateHopital;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use PS\ParametreBundle\Form\HopitalType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Hopital controller.
 */
class HopitalController extends Controller
{
    /**
     * Lists all hopital entities.
     *
     */
    public function indexAction(Request $request)
    {
        $user   = $this->getUser();
        $source = new Entity('ParametreBundle:Hopital');

        $grid = $this->get('grid');

        $source->manipulateQuery(function ($qb) use ($user) {
            if ($user->hasRole('ROLE_ADMIN_CORPORATE')) {
                $corporate = $user->getPersonne()->getCorporate();
                if ($corporate) {
                    $ids = $corporate->getHopitauxId();

                    $qb->andWhere('_a.id IN (:hopitaux)')->setParameter('hopitaux', $ids);
                }
            } else if ($user->hasRole('ROLE_ADMIN_LOCAL')) {
                $qb->andWhere('_a.id = :hopital')->setParameter('hopital', $user->getHopital()->getId());
            }
        });

        $grid->setSource($source);

        $rowAction = new RowAction('Détails', 'admin_parametre_hopital_show');
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_parametre_hopital_edit');
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'admin_parametre_hopital_delete', false, '_self', [], 'ROLE_ADMIN');
        $grid->addRowAction($rowAction);

        return $grid->getGridResponse('hopital/grid.html.twig');
    }

    /**
     * Creates a new hopital entity.
     *
     */
    public function newAction(Request $request)
    {
        $hopital = new Hopital();
        $form    = $this->createForm(HopitalType::class, $hopital, [
            'action' => $this->generateUrl('admin_parametre_hopital_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'admin_parametre_hopital_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                if ($corporate = $this->getUser()->getPersonne()->getCorporate()) {
                    $_corporteHopital = new CorporateHopital();
                    $_corporteHopital->setCorporate($corporate);
                    $hopital->addCorporate($_corporteHopital);
                }
                $em->persist($hopital);
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

        
        return $this->render('hopital/new.html.twig', [
            'hopital' => $hopital,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a hopital entity.
     *
     */
    public function showAction(Hopital $hopital)
    {
        $showForm = $this->createForm(HopitalType::class, $hopital);

        $deleteForm = $this->createDeleteForm($hopital);

        return $this->render('hopital/show.html.twig', [
            'hopital'     => $hopital,
            'show_form'   => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing hopital entity.
     *
     */
    public function editAction(Request $request, Hopital $hopital)
    {
        $deleteForm = $this->createDeleteForm($hopital);
        $form   = $this->createForm(HopitalType::class, $hopital, [
            'action' => $this->generateUrl('admin_parametre_hopital_edit', ['id' => $hopital->getId()]),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'admin_parametre_hopital_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                if ($corporate = $this->getUser()->getPersonne()->getCorporate()) {
                    $_corporteHopital = new CorporateHopital();
                    $_corporteHopital->setCorporate($corporate);
                    $hopital->addCorporate($_corporteHopital);
                }
                $em->persist($hopital);
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

        return $this->render('hopital/edit.html.twig', [
            'hopital'     => $hopital,
            'form'   => $form->createView(),
            
        ]);
    }

    /**
     * Deletes a hopital entity.
     *
     */
    public function deleteAction(Request $request, Hopital $hopital)
    {
        $form = $this->createDeleteForm($hopital);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($hopital);
            $em->flush();

            $redirect = $this->generateUrl('admin_parametre_hopital_index');

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

        return $this->render('hopital/delete.html.twig', ['form' => $form->createView(), 'hopital' => $hopital]);
    }

    /**
     * Creates a form to delete a hopital entity.
     *
     * @param Hopital $hopital The hopital entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Hopital $hopital)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametre_hopital_delete', ['id' => $hopital->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
