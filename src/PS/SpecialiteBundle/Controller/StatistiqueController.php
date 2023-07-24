<?php

namespace PS\SpecialiteBundle\Controller;

use PS\ParametreBundle\Entity\Specialite;
use PS\GestionBundle\Entity\Statistique;
use PS\GestionBundle\Form\StatistiqueType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Source\Entity;

class StatistiqueController extends Controller
{
    /**
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $specialites = $this->get('knp_paginator')->paginate(
            $em->getRepository(Specialite::class)->createQueryBuilder('u'),
            $request->query->get('page', 1) /*page number*/,
            20/*limit per page*/
        );

        return $this->render('PSSpecialiteBundle:Statistique:index.html.twig', [
            'specialites' => $specialites,
        ]);
    }

    /**
     * @param Request $request
     * @param Specialite $specialite
     */
    public function listAction(Request $request, Specialite $specialite)
    {
        $idSpecialite = $specialite->getId();
        $source       = new Entity(Statistique::class);
        $em = $this->getDoctrine()->getEntityManager();
        $repStat = $em->getRepository(Statistique::class);
        $source->initQueryBuilder($repStat->getBySpecialite($idSpecialite));

        $grid = $this->get('grid');

        $rowAction = new RowAction('Modifier', 'admin_specialite_stats_edit');

        /*$rowAction->manipulateRender(function ($action, $row) {
            $action->setAttributes(['class' => 'btn btn-success btn-sm']);
            $action->setTitle('<i class="fa fa-edit"></i>');
            return $action;
        });*/


         $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'PSSpecialiteBundle:Statistique:edit', 'parameters' => ['id' => $row->getField('id')]];
        });

        /*$rowAction->manipulateRender(function ($action, $row) {
        return ['controller' => 'PSSpecialiteBundle:TypeChamp:edit', 'parameters' => ['id' => $row->getField('id')]];
        });*/
        $grid->addRowAction($rowAction);

        

        $grid->setSource($source);
        //$serializer = $this->get('serializer');
        //$errors = $serializer->normalize($form);
        //
        return $grid->getGridResponse('PSSpecialiteBundle:Statistique:grid.html.twig', ['specialite' => $specialite]);
    }

    /**
     * @param Request $request
     * @param Specialite $specialite
     * @return mixed
     */
    public function newAction(Request $request, Specialite $specialite)
    {
        $em = $this->getDoctrine()->getManager();

        $idSpecialite = $specialite->getId();
        $statistique  = new Statistique();
        $form         = $this->createForm(StatistiqueType::class, $statistique
            , [
            'action'     => $this->generateUrl('admin_specialite_stats_new', ['id' => $specialite->getid()]),
            'method'     => 'POST',
            'specialite' => $idSpecialite,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($statistique);
            $em->flush();

            $this->addFlash('message', 'Champ crée avec succès');

            return $this->redirectToRoute('admin_specialite_stats_new', ['id' => $specialite->getId()]);
        }

        return $this->render('PSSpecialiteBundle:Statistique:new.html.twig', [
            'statistique' => $statistique,
            'form'  => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function editAction(Request $request, Statistique $statistique)
    {
        $specialite = $statistique->getChamp()->getEtape()->getSpecialite();

        $em = $this->getDoctrine()->getManager();

        $idSpecialite = $specialite->getId();
        
        $form         = $this->createForm(StatistiqueType::class, $statistique, [
            'action'     => $this->generateUrl('admin_specialite_stats_edit', ['id' => $statistique->getid()]),
            'method'     => 'POST',
            'specialite' => $idSpecialite,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($champ);
            $em->flush();

            $this->addFlash('message', 'Champ crée avec succès');

            return $this->redirectToRoute('admin_specialite_stats_list', ['id' => $specialite->getId()]);
        }

        return $this->render('PSSpecialiteBundle:Statistique:edit.html.twig', [
            'form' => $form->createView(),
            'statistique' => $statistique
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function deleteAction(Request $request, Statistique $statistique)
    {
        $em         = $this->getDoctrine()->getManager();
        $specialite = $statistique->getChamp()->getEtape()->getSpecialite();
        $form       = $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_specialite_stats_delete', ['id' => $statistique->getid()]))
            ->setMethod('DELETE')
            ->getForm();



        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->remove($statistique);
            $em->flush();

            $this->addFlash('message', 'Statistique Supprimé avec succès');

            return $this->redirectToRoute('admin_specialite_stats_list', ['id' => $specialite->getId()]);
        }

        return $this->render('PSSpecialiteBundle:Statistique:delete.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
