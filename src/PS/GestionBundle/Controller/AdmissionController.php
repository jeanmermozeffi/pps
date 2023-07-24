<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Entity\Admission;

use PS\GestionBundle\Entity\Patient;
use Symfony\Component\HttpFoundation\Request;
use PS\GestionBundle\Form\AdmissionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Admission controller.
 *
 * @Route("/admin/gestion/admission")
 */
class AdmissionController extends Controller
{
    /**
     * Lists all admission entities.
     *
     * @Route("/", name="gestion_admission_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {

        $source = new Entity(Admission::class);

        $params = [];

        $grid = $this->get('grid');

        $date = $request->query->get('date');

        $user = $this->getUser();

        $source->manipulateQuery(function ($qb) use ($user, $date) {

            if (!$user->hasRole('ROLE_CUSTOMER')) {
                $qb->andWhere("_a.hopital = :hopital");
                $qb->setParameter('hopital', $user->getHopital());
            } else {
                $qb->andWhere("_a.patient = :patient");
                $qb->setParameter('patient', $user->getPatient());
            }


            if ($date) {
                $qb->andWhere('DATE(_a.date) = :date');
                $qb->setParameter('date', $date);
            }
           

            $qb->orderBy('_a.date', 'DESC');

        });

        $grid->setSource($source);

        $grid->setRouteUrl($this->generateUrl('gestion_admission_index'));

        
        if ($this->isGranted('ROLE_INFIRMIER')) {
            $rowAction = new RowAction('Constantes', 'admin_gestion_infirmier_consultation');
            $rowAction->addManipulateRender(function ($action, $row) {

                $action->setRouteParameters(['id' => $row->getField('patient.id'), 'direct' => 1]);

                return $row;
            });
            $grid->addRowAction($rowAction);
        }

        /*$rowAction = new RowAction('Détails', 'gestion_admission_show');
       
        $grid->addRowAction($rowAction);

        /*$rowAction = new RowAction('Modifier', 'gestion_admission_edit');
        $grid->addRowAction($rowAction);

            /*$rowAction = new RowAction('Supprimer', 'gestion_admission_delete');
        $rowAction->setAttributes(['data-target' => '#stack2']);
        $grid->addRowAction($rowAction);*/
    


        return $grid->getGridResponse('gestion/admission/index.html.twig');
    }

    /**
     * Creates a new admission entity.
     *
     * @Route("/new", name="gestion_admission_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = $this->getUser();
        $admission = new Admission();
        $admission->setHopital($user->getHopital());
        $admission->setDate(new \DateTime());
        $form = $this->createForm(AdmissionType::class, $admission, [
                'action' => $this->generateUrl('gestion_admission_new'),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);

         

        
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'gestion_admission_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $patient = $em->getRepository(Patient::class)->findOneBy([
                    'identifiant' => $form->get('identifiant')->getData(),
                    'pin' => $form->get('pin')->getData()
                ]);

                if ($patient) {
                    $admission->setPatient($patient);
                    $em->persist($admission);
                    $em->flush();
                    $message       = 'Opération effectuée avec succès';
                    $statut = 1;
                    $this->addFlash('success', $message);
                } else {
                    $statut = 0;
                    $message = ['Couple ID/PIN inexistant, ce patient inexistant dans notre base de données'];
                }
                
                
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


        return $this->render('gestion/admission/new.html.twig', [
            'admission' => $admission,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a admission entity.
     *
     * @Route("/{id}/show", name="gestion_admission_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Admission $admission)
    {
            $deleteForm = $this->createDeleteForm($admission);
        $showForm = $this->createForm(AdmissionType::class, $admission);
    $showForm->handleRequest($request);


    return $this->render('gestion/admission/show.html.twig', [
            'admission' => $admission,
            'show_form' => $showForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ]);
    }

    /**
     * Displays a form to edit an existing admission entity.
     *
     * @Route("/{id}/edit", name="gestion_admission_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Admission $admission)
    {
        //$deleteForm = $this->createDeleteForm($admission);
        $form = $this->createForm(AdmissionType::class, $admission, [
                'action' => $this->generateUrl('gestion_admission_edit', ['id' => $admission->getId()]),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);
       
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'gestion_admission_index';
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

        return $this->render('gestion/admission/edit.html.twig', [
            'admission' => $admission,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a admission entity.
     *
     * @Route("/{id}/delete", name="gestion_admission_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Admission $admission)
    {
        $form = $this->createDeleteForm($admission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($admission);
            $em->flush();

            $redirect = $this->generateUrl('gestion_admission_index');

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

        return $this->render('gestion/admission/delete.html.twig', [
            'admission' => $admission,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a admission entity.
     *
     * @param Admission $admission The admission entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Admission $admission)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'gestion_admission_delete'
                ,   [
                        'id' => $admission->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
