<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Entity\PatientConstante;
use PS\GestionBundle\Entity\Patient;
use Symfony\Component\HttpFoundation\Request;
use PS\GestionBundle\Form\PatientConstanteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\HttpFoundation\RedirectResponse;
use PS\ParametreBundle\Entity\Constante;


/**
 * PatientConstante controller.
 *
 * @Route("admin/patient/constante", options={"expose"=true})
 */
class PatientConstanteController extends Controller
{

    /**
     * @return mixed
     */
    public function getManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @param $class
     * @return mixed
     */
    public function getRepository($class)
    {
        return $this->getManager()->getRepository($class);
    }
    /**
     * Lists all patientConstante entities.
     *
     * @Route("/", name="gestion_patientconstante_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {

        $constantes = $this->getRepository(Constante::class)->findAll();

        return $this->render('gestion/patientconstante/index.html.twig', [
            'constantes' => $constantes
        ]);
    }


     /**
     * Creates a new patientConstante entity.
     *
     * @Route("/data/{id}", name="gestion_patientconstante_data", options={"expose"=true})
     * @Route("/data/{id}/{patient}", name="gestion_patientconstante_data_patient", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function dataAction(Request $request, Constante $constante, Patient $patient = null)
    {
        $annee = $request->query->get('annee');
        

        if ($constante->getValType() == 1) {
            $data = $this->getRepository(PatientConstante::class)->data($constante, $this->getPatient($patient), $annee);

            $result = [];
            $values = [];
    
           foreach ($data as $annee => $values) {
                $mois = array_column($values, 'mois');
                $valeurs = array_column($values, 'val_moy');
    
                $map = array_combine($mois, $valeurs);
    
                $result[$annee] = [];
    
                for ($i = 1; $i <= 12; $i++) {
                    if (!isset($map[$i])) {
                        $result[$annee][] = 0;
                    } else {
                       $result[$annee][] = intval($map[$i]);
                    }
                }
            }
                
           
    
            return new JsonResponse($result);
        }


        $data = $this->getRepository(PatientConstante::class)->calendarData($constante, $this->getPatient($patient), $annee);
        
        return new JsonResponse($data);

      
    }


    /**
     * Creates a new patientConstante entity.
     *
     * @Route("/new/{id}", name="gestion_patientconstante_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Constante $constante, Patient $patient = null)
    {
         $annee = $request->query->get('annee');
        $patientConstante = new PatientConstante();
        $patientConstante->setPatient($this->getPatient($patient));
        $patientConstante->setConstante($constante);
        $params = ['id' => $constante->getId(), 'annee' => $annee];
        $form = $this->createForm(PatientConstanteType::class, $patientConstante, [
                'action' => $this->generateUrl('gestion_patientconstante_new', $params),
                'method' => 'POST',
                'constante' => $constante
            ]);
        $form->handleRequest($request);

         

        $ajax = true;
        if ($form->isSubmitted()) {
            $response = [];
            //$params = [];
            $redirectRoute = 'gestion_patientconstante_show';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($patientConstante);
                $em->flush();
                $message       = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);
                $ajax = false;
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


        return $this->render('gestion/patientconstante/new.html.twig', [
            'patientConstante' => $patientConstante,
            'constante' => $constante,
            'form' => $form->createView(),
        ]);
    }


    public function getPatient($patient = null)
    {
        $_patient = $this->getUser()->getPatient();
       return $patient && $_patient->isParentOf($patient) ? $patient: $_patient; 
    }

    /**
     * Finds and displays a patientConstante entity.
     *
     * @Route("/{id}/show", name="gestion_patientconstante_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Constante $constante)
    {
        $patient = $this->getPatient();
        $annee  = $request->query->get('annee');
        $annees = range($this->getRepository(PatientConstante::class)->minYear($constante, $patient), date('Y'));


        return $this->render('gestion/patientconstante/show.html.twig', [
            'constante'  => $constante,
            'patient' => $patient,
            'annee'   => $annee,
            'annees'  => $annees,
        ]);
    }

    /**
     * Displays a form to edit an existing patientConstante entity.
     *
     * @Route("/{id}/edit", name="gestion_patientconstante_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PatientConstante $patientConstante)
    {
        //$deleteForm = $this->createDeleteForm($patientConstante);
        $form = $this->createForm(PatientConstanteType::class, $patientConstante, [
                'action' => $this->generateUrl('gestion_patientconstante_edit', ['id' => $patientConstante->getId()]),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);
       
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'gestion_patientconstante_index';
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

        return $this->render('gestion/patientconstante/edit.html.twig', [
            'patientConstante' => $patientConstante,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a patientConstante entity.
     *
     * @Route("/{id}/delete", name="gestion_patientconstante_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, PatientConstante $patientConstante)
    {
        $form = $this->createDeleteForm($patientConstante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($patientConstante);
            $em->flush();

            $redirect = $this->generateUrl('gestion_patientconstante_index');

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

        return $this->render('gestion/patientconstante/delete.html.twig', [
            'patientConstante' => $patientConstante,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a patientConstante entity.
     *
     * @param PatientConstante $patientConstante The patientConstante entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PatientConstante $patientConstante)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'gestion_patientconstante_delete'
                ,   [
                        'id' => $patientConstante->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
