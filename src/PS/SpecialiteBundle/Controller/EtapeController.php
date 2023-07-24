<?php

namespace PS\SpecialiteBundle\Controller;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\SpecialiteBundle\Entity\Etape;
use PS\SpecialiteBundle\Form\EtapeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EtapeController extends Controller
{
    /**
     * @return mixed
     */
    public function indexAction()
    {
        $source = new Entity(Etape::class);

        $grid = $this->get('grid');

        $rowAction = new RowAction('Modifier', 'admin_specialite_etape_edit');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'PSSpecialiteBundle:Etape:edit', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        $grid->setSource($source);
        //$serializer = $this->get('serializer');
        //$errors = $serializer->normalize($form);
        //
        return $grid->getGridResponse('PSSpecialiteBundle:Etape:index.html.twig', []);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function newAction(Request $request)
    {
        $etape = new Etape();
        $form  = $this->createForm(EtapeType::class, $etape, [
            'action' => $this->generateUrl('admin_specialite_etape_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($etape);
            $em->flush();

            return $this->redirectToRoute('admin_specialite_etape_index');
        }

        return $this->render('PSSpecialiteBundle:Etape:new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Etape $etape
     * @return mixed
     */
    public function editAction(Request $request, Etape $etape)
    {
        $id = $etape->getId();
        $form = $this->createForm(EtapeType::class, $etape, [
            'action' => $this->generateUrl('admin_specialite_etape_edit', ['id' => $id]),
            'method' => 'POST',
            'id' => $id
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_specialite_etape_index');
        }
        return $this->render('PSSpecialiteBundle:Etape:edit.html.twig', [
            'form' => $form->createView(),
            'etape' => $etape
            // ...
        ]);
    }

}
