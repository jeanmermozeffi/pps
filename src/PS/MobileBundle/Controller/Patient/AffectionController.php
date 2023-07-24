<?php

namespace PS\MobileBundle\Controller\Patient;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use PS\GestionBundle\Entity\Patient;
use PS\SiteBundle\Form\PatientRechercherForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use PS\MobileBundle\Controller\ApiTrait;
use PS\ParametreBundle\Entity\PatientAffections;
use PS\ParametreBundle\Form\PatientAffectionsType;

class AffectionController extends Controller
{
    use ApiTrait;

    /**
     * @Rest\View(serializerGroups={"patient-affection"})
     * @Rest\Get("/patients/{id}/affections")
     */
    public function getAffectionsAction(Request $request)
    {
        $patient = $this->getPatient($request->get('id'));
        return $patient->getAffections();
    }


    
    /**
     * Retourne l'Affection du patient
     *
     * @param integer $patient
     * @param integer $id
     * @return PatientAffections|null
     */
    private function getAffection(int $patient, int $id): ?PatientAffections
    {
        return $this->getRepository(PatientAffections::class)->findOneBy(compact('patient', 'id'));
    }

    /**
     * @Rest\View(serializerGroups={"patient-affection"})
     * @Rest\Get("/patients/{id}/affections/{affection}")
     */
    public function getAffectionAction(Request $request)
    {
        $patientAffection = $this->getAffection($request->get('id'), $request->get('affection'));

        if ($patientAffection) {
            return $patientAffection;
        }

        return $this->notFound('Affection avec ID '.$request->get('affection').' inexistant ou n\'est pas associé à votre compte');
    }


     /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/patients/{id}/affections/{affection}")
     */
    public function deleteAffectionAction(Request $request)
    {
        $patientAffection = $this->getAffection($request->get('id'), $request->get('affection'));

        $patient = $this->getPatient($request->get('id'));

        if ($patientAffection) {
            $patient->removeAffection($patientAffection);
            $this->getManager()->flush();
            return new Response();
        }

        return $this->notFound('Affection avec ID '.$request->get('affection').' inexistante ou n\'est pas associé à votre compte');
    }


     /**
     * @Rest\View(serializerGroups={"patient-affection"}, statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/patients/{id}/affections")
     */
    public function postAffectionAction(Request $request)
    {
        $patient = $this->getPatient( $request->get('id'));

        $patientAffection = new PatientAffections();
        $patientAffection->setPatient($patient);

        $form = $this->createForm(PatientAffectionsType::class, $patientAffection, [
            'csrf_protection' => false,
            'date_format' => 'api'
        ]);

         $data = $this->formatValue($request->request->all());


        $form->submit($data);

        if ($form->isValid()) {
            $em = $this->getManager();
            $em->persist($patientAffection);
            $em->flush();
            return $patientAffection;
        }

        return $form;
    }



     /**
     * @Rest\View(serializerGroups={"patient-affection"})
     * @Rest\Put("/patients/{id}/affections/{affection}")
     */
    public function putAffectionAction(Request $request)
    {
        $patientAffection = $this->getAffection( $request->get('id'), $request->get('affection') );
        //$data = $this->formatValue($request->request->all());

        $form = $this->createForm(PatientAffectionsType::class, $patientAffection, [
            'csrf_protection' => false,
            'date_format' => 'api'
        ]);


        //echo json_encode($this->formatValue($request->request->all()));


        $form->submit($this->formatValue($request->request->all()));

        if ($form->isValid()) {
            $em = $this->getManager();
             //$em->persist($patientAffection);
            $em->flush();
            return $patientAffection;
        }

        return $form;
    }
}
