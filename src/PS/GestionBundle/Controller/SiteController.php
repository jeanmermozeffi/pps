<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Entity\Site;
use PS\GestionBundle\Form\SiteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\ActionsColumn;
use APY\DataGridBundle\Grid\Column;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\HttpFoundation\JsonResponse;


class SiteController extends Controller
{
    /**
     * @return mixed
     */
    public function indexAction()
    {

        $source = new Entity(Site::class);

        $grid = $this->get('grid');

        $grid->setSource($source);

        $grid->getColumn('statut')->manipulateRenderCell(function ($value) {
            if ($value) {
                return '<span class="label label-success">Actif</span>';
            }
            return '<span class="label label-danger">Inactif</span>';
        });

        /*$rowAction = new RowAction('Détails', 'admin_gestion_site_show');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'GestionBundle:Site:show', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);*/

        $rowAction = new RowAction('Modifier', 'admin_gestion_site_edit');

        /*$rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'GestionBundle:Site:edit', 'parameters' => ['id' => $row->getField('id')]];
        });*/

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'admin_gestion_site_delete');
        /*$rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'GestionBundle:Site:delete', 'parameters' => ['id' => $row->getField('id')]];
        });*/
        $grid->addRowAction($rowAction);

        return $grid->getGridResponse('GestionBundle:Site:grid.html.twig');

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function newAction(Request $request)
    {
        $em         = $this->getDoctrine()->getManager();
        $site = new Site();
        $form       = $this->createForm(SiteType::class, $site, [
            'action' => $this->generateUrl('admin_gestion_site_new'),
            'method' => 'POST',
            'validation_groups' => ['Default', 'FileRequired']
        ]);
        $form->handleRequest($request);

        $redirect = $this->generateUrl('admin_gestion_site_index');

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $em->persist($site);
                $em->flush();
                $statut  = 1;
                $message = 'Opération effectuée avec succès';
            } else {
                $message = $this->get('app.form_error')->all($form);
                $statut  = 0;

            }

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(compact('statut', 'message', 'redirect'));
            }

            return $this->redirect($redirect);
        }
        
        return $this->render('GestionBundle:Site:new.html.twig', [
            'site' => $site,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Site $site
     * @return mixed
     */
    public function editAction(Request $request, Site $site)
    {
        $em         = $this->getDoctrine()->getManager();
       
        $form       = $this->createForm(SiteType::class, $site, [
            'action' => $this->generateUrl('admin_gestion_site_edit', ['id' => $site->getId()]),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        $redirect = $this->generateUrl('admin_gestion_site_index');

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                //$em->persist($site);
                $em->flush();
                $statut  = 1;
                $message = 'Opération effectuée avec succès';
            } else {
                $message = $this->get('app.form_error')->all($form);
                $statut  = 0;

            }

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(compact('statut', 'message', 'redirect'));
            }

            return $this->redirect($redirect);
        }
        


       

        return $this->render('GestionBundle:Site:edit.html.twig', [
            'site' => $site,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @param Site $site
     * @return mixed
     */
    public function showAction(Site $site)
    {
        return $this->render('GestionBundle:Site:show.html.twig', [
            'site' => $site,
        ]);
    }

    /**
     * @param Request $request
     * @param Site $site
     * @return mixed
     */
    public function deleteAction(Request $request, Site $site)
    {
        $form = $this->createDeleteForm($site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($site);
            $em->flush();


            $redirect = $this->generateUrl('admin_gestion_site_index');

            $message = 'Opération effectuée avec succès';

            $response = [
                'statut'   => 1,
                'message'  => $message,
                'redirect' => $redirect
            ];

            $this->addFlash('success', $message);

            if (!$request->isXmlHttpRequest()) {
                return $this->redirect($redirect);
            } else {
                return new JsonResponse($response);
            }
        }

        return $this->render('GestionBundle:Site:delete.html.twig', [
            'site' => $site,
            'form' => $form->createView(),
        ]);
    }


    /**
     * Creates a form to delete a specialite entity.
     *
     * @param Specialite $specialite The specialite entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Site $site)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_gestion_site_delete', ['id' => $site->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

}
