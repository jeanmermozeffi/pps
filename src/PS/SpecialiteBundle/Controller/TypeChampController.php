<?php

namespace PS\SpecialiteBundle\Controller;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\SpecialiteBundle\Entity\TypeChamp;
use PS\SpecialiteBundle\Form\TypeChampType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TypeChampController extends Controller
{
    /**
     * @return mixed
     */
    public function indexAction()
    {
        $source = new Entity(TypeChamp::class);

        $grid = $this->get('grid');

        $rowAction = new RowAction('Modifier', 'admin_specialite_typechamp_edit');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'PSSpecialiteBundle:TypeChamp:edit', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        $grid->setSource($source);
        //$serializer = $this->get('serializer');
        //$errors = $serializer->normalize($form);
        //
        return $grid->getGridResponse('PSSpecialiteBundle:TypeChamp:index.html.twig', []);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function newAction(Request $request)
    {
        $typechamp = new TypeChamp();
        $form  = $this->createForm(TypeChampType::class, $typechamp, [
            'action' => $this->generateUrl('admin_specialite_typechamp_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typechamp);
            $em->flush();

            return $this->redirectToRoute('admin_specialite_typechamp_index');
        }

        return $this->render('PSSpecialiteBundle:TypeChamp:new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param TypeChamp $typechamp
     * @return mixed
     */
    public function editAction(Request $request, TypeChamp $typechamp)
    {
       
        
        $form = $this->createForm(TypeChampType::class, $typechamp, [
            'action' => $this->generateUrl('admin_specialite_typechamp_edit', ['id' => $typechamp->getId()]),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_specialite_typechamp_index');
        }
        return $this->render('PSSpecialiteBundle:TypeChamp:edit.html.twig', [
            'form' => $form->createView(),
            'typechamp' => $typechamp
            // ...
        ]);
    }

}
