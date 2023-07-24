<?php

namespace PS\SpecialiteBundle\Controller;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\ParametreBundle\Entity\Specialite;
use PS\SpecialiteBundle\Entity\Champ;
use PS\SpecialiteBundle\Entity\Etape;
use PS\SpecialiteBundle\Form\ChampType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class ChampController extends Controller
{
    /**
     * @param Request $request
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

        return $this->render('PSSpecialiteBundle:Champ:index.html.twig', [
            'specialites' => $specialites,
        ]);
    }

    /**
     * @param Request $request
     * @param Specialite $specialite
     * @return mixed
     */
    public function gridAction(Request $request, Specialite $specialite)
    {
        $idSpecialite = $specialite->getId();
        $source       = new Entity(Champ::class);

        $source->manipulateQuery(function ($qb) use ($idSpecialite) {
            return $qb->andWhere('_a.specialite = :specialite')->setParameter('specialite', $idSpecialite);
        });

        $grid = $this->get('grid');

        $rowAction = new RowAction('Modifier', 'admin_specialite_champ_edit');

        $rowAction->manipulateRender(function ($action, $row) {
            $action->setAttributes(['class' => 'btn btn-success btn-sm']);
            $action->setTitle('<i class="fa fa-edit"></i>');
            return $action;
        });

        /*$rowAction->manipulateRender(function ($action, $row) {
        return ['controller' => 'PSSpecialiteBundle:TypeChamp:edit', 'parameters' => ['id' => $row->getField('id')]];
        });*/
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'admin_specialite_champ_delete');

        $rowAction->manipulateRender(function ($action, $row) {
            $action->setAttributes(['class' => 'btn btn-danger btn-sm']);
            $action->setTitle('<i class="fa fa-remove"></i>');
            return $action;
        });

        $grid->addRowAction($rowAction);

        $grid->setSource($source);
        //$serializer = $this->get('serializer');
        //$errors = $serializer->normalize($form);
        //
        return $grid->getGridResponse('PSSpecialiteBundle:Champ:grid.html.twig', ['specialite' => $specialite]);
    }

    /**
     * @param Request $request
     * @param Specialite $specialite
     * @return mixed
     */
    public function newAction(Request $request, Specialite $specialite)
    {
        $em           = $this->getDoctrine()->getManager();
        $maxChamp     = $em->getRepository(Champ::class)->getMaxCount();
        $idSpecialite = $specialite->getId();
        $champ        = new Champ();
        $form         = $this->createForm(ChampType::class, $champ, [
            'action' => $this->generateUrl('admin_specialite_champ_new', ['id' => $specialite->getid()]),
            'method' => 'POST',
            'specialite' => $idSpecialite
        ]);

        $champ->setSpecialite($specialite);
        $champ->setAliasChamp('field__' . $idSpecialite . '_' . bin2hex(random_bytes(5)));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($champ);
            $em->flush();

            $this->addFlash('message', 'Champ crée avec succès');

            return $this->redirectToRoute('admin_specialite_champ_new', ['id' => $specialite->getId()]);
        }

        return $this->render('PSSpecialiteBundle:Champ:new.html.twig', [
            'champ' => $champ,
            'form'  => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Champ $champ
     * @return mixed
     */
    public function editAction(Request $request, Champ $champ)
    {
        $em         = $this->getDoctrine()->getManager();
        $specialite = $champ->getSpecialite();
        $form       = $this->createForm(ChampType::class, $champ, [
            'action' => $this->generateUrl('admin_specialite_champ_edit', ['id' => $champ->getid()]),
            'method' => 'POST',
            'specialite' => $specialite->getId()
        ]);

        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($champ);
            $em->flush();

            $this->addFlash('message', 'Champ modifié avec succès');

            return $this->redirectToRoute('admin_specialite_champ_list', ['id' => $specialite->getId()]);
        }

        return $this->render('PSSpecialiteBundle:Champ:edit.html.twig', [
            'champ' => $champ,
            'form'  => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Champ $champ
     * @return mixed
     */
    public function deleteAction(Request $request, Champ $champ)
    {
        $em         = $this->getDoctrine()->getManager();
        $specialite = $champ->getSpecialite();
        $form       = $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_specialite_champ_delete', ['id' => $champ->getid()]))
            ->setMethod('DELETE')
            ->getForm();

        /*if (!$champ->getAliasChamp()) {
        $champ->setAliasChamp('field__' . $specialite->getId() . '_' . bin2hex(random_bytes(5)));
        $champ->setPropChamp([]);
        }*/

        //$champ->setSpecialite($specialite);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->remove($champ);
            $em->flush();

            $this->addFlash('message', 'Champ Supprimé avec succès');

            return $this->redirectToRoute('admin_specialite_champ_list', ['id' => $specialite->getId()]);
        }

        return $this->render('PSSpecialiteBundle:Champ:delete.html.twig', [
            'champ' => $champ,
            'form'  => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Specialite $specialite
     */
    public function showAction(Request $request, Specialite $specialite, Etape $etape = null)
    {
        $champs = $specialite->getChamps();
        $em     = $this->getDoctrine()->getEntityManager();

        if ($etape) {

        }


        $champs = $champs->filter(function ($champ) use($etape) {
            //dump($champ->getTypeChamp()->getAliasTypeChamp());
            return is_null($champ->getChampParent()) && (is_null($etape) || $champ->getEtape() == $etape);
        });

        //dump($champs);exit;
        //exit;

        

        $repChamp = $em->getRepository(champ::class);
        $repEtape = $em->getRepository(Etape::class);

        /*if ($etape) {
        $hasField = $repEtape->hasField($specialite, $etape);
        } else {
        $hasField = false;
        }*/

        $collections = collect($champs)->groupBy(function ($champ) {
            return $champ->getEtape()->getId();
        });

        $result = [];

        $collections->each(function (&$item, $key) use (&$result) {
            $libEtape = $item[0]->getEtape()->getLibEtape();
            $all      = $item->all();

            $result[$libEtape] = $item->all();
        });

        $etapes = $repEtape->findBy(['etapeParente' => null]);

        

        //$formBuilder = $this->createFormBuilder();

        /*foreach ($champs as $champ) {

        if (!$champ->getChampParent()) {

        } else {

        }

        $label            = $champ->getLibChamp();
        $aliasType        = $champ->getTypeChamp()->getAliasTypeChamp();
        $defaultNamespace = 'Symfony\\Component\\Form\\Extension\\Core\\Type\\';
        $valeurs          = $champ->getValeurAutoriseeChamp();
        $type             = $defaultNamespace . $aliasType;
        $enfants          = $champ->getChampEnfants();
        $parent           = $champ->getChampParent();

        if ($parent) {

        $parentType = $parent->getTypeChamp()->getAliasTypeChamp();
        } else {
        $parentType = null;
        }

        $parentValue = $champ->getValeurChampParent();

        $properties = ['label' => $label, 'attr' => []];

        $valeurDefaut = $champ->getValeurChampDefaut();
        $isRequired   = (bool) $champ->getChampRequis();
        $propChamp    = $champ->getPropChamp();

        if ($label == '_') {
        $label = false;
        }
        //dump($champ->getTypeChamp()->getAliasTypeChamp());

        if ($aliasType == 'BoolType') {
        $type       = 'Symfony\\Component\\Form\\Extension\\Core\\Type\\ChoiceType';
        $choices    = array_combine(['oui', 'non'], ['oui', 'non']);
        $properties = [
        'choices'  => $choices,
        'expanded' => true,
        'multiple' => false,
        'label'    => $label,
        ];
        } elseif ($aliasType == 'RadioType') {
        $type       = 'Symfony\\Component\\Form\\Extension\\Core\\Type\\ChoiceType';
        $properties = [
        'choices'  => array_combine($valeurs, $valeurs),
        'expanded' => true,
        'multiple' => false,
        'label'    => $label,
        ];
        //$choices = [];

        } elseif ($aliasType == 'ChoiceType') {

        $properties = [
        'choices'  => array_combine($valeurs, $valeurs),
        'expanded' => true,
        'multiple' => true,
        'label'    => $label,
        ];

        //$choices = $champ->getValeurAutoriseeChamp();

        } elseif ($aliasType == 'VoidType') {
        $type       = str_replace('VoidType', 'TextType', $type);
        $properties = [
        'attr'     => ['class' => 'hide is-hidden remove-input'],
        'required' => false,
        'label'    => $label,
        ];
        } elseif ($aliasType == 'DateType') {
        $properties = [
        'attr'   => ['class' => 'datepicker'],
        'widget' => 'single_text'
        , 'format' => 'dd/MM/yyyy'
        , 'label' => $label,
        ];
        } elseif ($aliasType == 'DateTimeType') {
        $type       = 'collot_datetime';
        $properties = [
        'label' => $label
        , 'widget' => 'single_text'
        , 'attr' => ['autocomplete' => 'off']
        , 'pickerOptions' => [
        'format'             => 'dd/mm/yyyy hh:ii',
        'weekStart'          => 1,
        //'startDate' => date('Y-m-d'), //example
        //'endDate' => '01/01/3000', //example
        'autoclose'          => true,
        'startView'          => 'month',
        'minView'            => 'hour',
        'maxView'            => 'decade',
        'todayBtn'           => true,
        'todayHighlight'     => true,
        'keyboardNavigation' => true,
        'language'           => 'fr',
        'forceParse'         => false,
        'minuteStep'         => 5,
        'pickerPosition'     => 'bottom-right',
        'viewSelect'         => 'hour',
        'showMeridian'       => false,
        ],
        ];
        }

        if ($parentType && in_array($parentType, ['BoolType', 'RadioType'])) {

        $attr = [
        'class' => 'row-hide'
        , 'data-parent' => $parent->getAliasChamp()
        , 'data-parent-value' => $parentValue,
        ];
        if (isset($properties['attr'])) {
        if (isset($properties['attr']['class'])) {
        $oldClass = $properties['attr']['class'];

        $attr['class'] .= ' ' . $oldClass;

        //$properties['attr'] = $attr;
        }
        }

        $properties['attr'] = $attr;
        }

        if ($enfants->count()) {
        //dump($enfants);exit;
        $oldAttr = isset($properties['attr']) ? $properties['attr'] : [];
        foreach ($enfants as $enfant) {
        $children[] = $enfant->getAliasChamp();

        }
        $oldAttr = array_merge($oldAttr, ['data-children' => implode(' ', $children)]);
        $properties['attr'] = $oldAttr;
        }

        $formBuilder->add($champ->getAliasChamp(), $type, $properties);
        }*/


        $this->get('app.form_builder')->load($champs);

        $formBuilder = $this->get('app.form_builder')->getForm();

        $formBuilder->add('save', SubmitType::class, ['label' => 'Valider']);

        $form = $formBuilder->getForm();

        //dump($form);

        if ($request->isMethod('POST')) {
            $form->submit($request->request->all());

            if ($form->isValid()) {
                //dump($form);exit;
            }
        }

        return $this->render('PSSpecialiteBundle:Champ:show.html.twig', [

            'etapes'     => $etapes,
            'etape' => $etape,
            'specialite' => $specialite,
            //'champ' => $champ,
            'form'       => $form->createView(),
        ]);
    }
}
