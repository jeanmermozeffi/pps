<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Entity\Abonnement;
use PS\ParametreBundle\Entity\Pass;
use PS\ParametreBundle\Entity\Pack;
use Symfony\Component\HttpFoundation\Request;
use PS\GestionBundle\Form\AbonnementType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PS\GestionBundle\Form\SearchType;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\HttpFoundation\RedirectResponse;
use PS\GestionBundle\Form\CommandePackType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
/**
 * Abonnement controller.
 *
 */
class AbonnementController extends Controller
{
    /**
     * Lists all abonnement entities.
     *
     */
    public function indexAction(Request $request)
    {

        $patient = $this->getUser()->getPatient();

        $lastAbo = $patient->getLastPack();

        $source = new Entity(Abonnement::class);

        $source->manipulateQuery(function ($qb) use($patient) {
            return $qb->andWhere('_a.patient = :patient')->setParameter('patient', $patient);
        });

        $grid = $this->get('grid');

        $grid->setSource($source);


        $rowAction = new RowAction('Détails', 'admin_gestion_abonnement_show');
       
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_gestion_abonnement_edit');
        $grid->addRowAction($rowAction);

            /*$rowAction = new RowAction('Supprimer', 'admin_gestion_abonnement_delete');
        $rowAction->setAttributes(['data-target' => '#stack2']);
        $grid->addRowAction($rowAction);*/
    


        return $grid->getGridResponse('gestion/abonnement/index.html.twig', [
            'last_abo' => $lastAbo,
            'patient' => $patient
        ]);
    }


    public function searchAction(Request $request)
    {
        $form = $this->createPassForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $identifiant = $form->get('identifiant')->getData();

            $pin = $form->get('pin')->getData();

            $pass = $em->getRepository(Pass::class)->findOneBy(compact('identifiant', 'pin'));


            return $this->redirectToRoute('admin_gestion_abonnement_commande', ['pass' => $pass->getId()]);

        }

        return $this->render('gestion/abonnement/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    public function commandeAction(Request $request,Pass $pass = null)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $patient = $this->getUser()->getPatient();

        $identifiant = $patient->getIdentifiant();
        $pin = $patient->getPin();

        $pass = $pass ?? $em->getRepository(Pass::class)->findOneBy(compact('identifiant', 'pin'));



        $lastAbo = $patient->getLastPack();

        $form = $this->createForm($this->get('app.commande_pack_type')/*CommandePackType::class*/, null, [
            'action' => $this->generateUrl('admin_gestion_abonnement_commande', ['pass' => $pass->getId()]),
            'pack' => $lastAbo ? $lastAbo->getPack(): null
        ]);

        $commande = $session->get('commande');

        $form->get('pin')->setData($pass->getPin());
        $form->get('contact')->setData($commande['contact']);

        if (isset($commande['pack'])) {
            $form->get('pack')->setData($commande['pack']->getId());
        }
        //$form->get('qte')->setData(1);
        $form->get('identifiant')->setData($pass->getIdentifiant());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $session->set('commande', $form->getData());
            if ($form->has('code')) {
                $session->set('code', $form->get('code')->getData());
            }
            if (!$session->get('code')) {
                return $this->redirect($this->generateUrl('admin_gestion_abonnement_commande', ['pass' => $pass->getId()]));
            } else {
                $abonnement = new Abonnement();
                $pack = $form->get('pack')->getData();
                $previousAbonnement = $patient->getAbonnements()->filter(function($abonnement) {
                    return $abonnement->getPack()->getAlias() == 'PACK_SUBSCRIPTION';
                })->last();

                if ($pack->getAlias() == 'PACK_SUBSCRIPTION') {
                    $dateDebut = $previousAbonnement->getDateFin();
                } else {
                    $dateDebut = new \DateTime();
                }

                $dateDebutC = clone $dateDebut;

                $dateFin = $dateDebutC->modify($pack->getFullDuree());
                $abonnement->setPass($pass);
                $abonnement->setPack($pack);
                $abonnement->setPatient($patient);
                $em->persist($abonnement);
                $em->flush();

                $this->addFlash('success', 'Opération effectuée avec succès');

                return $this->redirect($this->generateUrl('admin_gestion_abonnement_index'));

            }
           
        }

        $pack = $em->getRepository(Pack::class)->findOneByOrdre(-1);
        return $this->render('gestion/abonnement/commande.html.twig', [
            'pass' => $pass,
            'pack' => $pack,
            'patient' => $patient,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @return mixed
     */
    private function createPassForm()
    {
        $form = $this->createForm(SearchType::class, [
            'action' => $this->generateUrl('admin_gestion_abonnement_search'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit', ['label' => 'Rechercher']);

        return $form;
    }

    /**
     * Creates a new abonnement entity.
     *
     */
    public function newAction(Request $request)
    {
        $abonnement = new Abonnement();
        $form = $this->createForm(AbonnementType::class, $abonnement, [
                'action' => $this->generateUrl('admin_gestion_abonnement_new'),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);

         

        
        if ($form->isSubmitted()) {
            $response = [];
            $redirectRoute = 'admin_gestion_abonnement_index';
            $redirect = $this->generateUrl($redirectRoute);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($abonnement);
                $em->flush();
                $message       = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);
            } else {
                $message = $this->get('ppca.form_error')->all($form);
                $statut = 0;
                $this->addFlash('warning', $message);
            }

            

            if ($request->isXmlHttpRequest()) {
                $response = compact('statut', 'message', 'redirect');
                return new JsonResponse($response);
            } else {
                return $this->redirect($redirect);
            }

        }


        return $this->render('gestion/abonnement/new.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a abonnement entity.
     *
     */
    public function showAction(Request $request, Abonnement $abonnement)
    {
            $deleteForm = $this->createDeleteForm($abonnement);
        $showForm = $this->createForm(AbonnementType::class, $abonnement);
    $showForm->handleRequest($request);


    return $this->render('gestion/abonnement/show.html.twig', [
            'abonnement' => $abonnement,
            'show_form' => $showForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ]);
    }

    /**
     * Displays a form to edit an existing abonnement entity.
     *
     */
    public function editAction(Request $request, Abonnement $abonnement)
    {
        //$deleteForm = $this->createDeleteForm($abonnement);
        $form = $this->createForm(AbonnementType::class, $abonnement, [
                'action' => $this->generateUrl('admin_gestion_abonnement_edit', array('id' => $abonnement->getId())),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);
       
        if ($form->isSubmitted()) {
            $response = [];
            $redirectRoute = 'admin_gestion_abonnement_index';
            $redirect = $this->generateUrl($redirectRoute);
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $message       = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);
            } else {
                $message = $this->get('ppca.form_error')->all($form);
                $statut = 0;
                $this->addFlash('warning', $message);
            }


            if ($request->isXmlHttpRequest()) {
                $response = compact('statut', 'message', 'redirect');
                return new JsonResponse($response);
            } else {
                return $this->redirect($redirect);
            }

            
        }

        return $this->render('gestion/abonnement/edit.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a abonnement entity.
     *
     */
    public function deleteAction(Request $request, Abonnement $abonnement)
    {
        $form = $this->createDeleteForm($abonnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($abonnement);
            $em->flush();

            $redirect = $this->generateUrl('admin_gestion_abonnement_index');

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

        return $this->render('gestion/abonnement/delete.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a abonnement entity.
     *
     * @param Abonnement $abonnement The abonnement entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Abonnement $abonnement)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'admin_gestion_abonnement_delete'
                ,   [
                        'id' => $abonnement->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
