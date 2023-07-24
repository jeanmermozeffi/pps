<?php

namespace PS\MobileBundle\Controller\Patient;

use FOS\RestBundle\View\View;
use PS\GestionBundle\Entity\Patient;
use PS\MobileBundle\Controller\ApiTrait;
use PS\SiteBundle\Form\PatientRechercherForm;
use Symfony\Component\HttpFoundation\Request;
use PS\ParametreBundle\Entity\PatientTraitement;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use PS\ParametreBundle\Form\PatientTraitementsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TraitementController extends Controller
{
    use ApiTrait;

    /**
     * @Rest\View(serializerGroups={"patient-traitement"})
     * @Rest\Get("/patients/{id}/traitements")
     */
    public function getTraitementsAction(Request $request)
    {
        $patient = $this->getPatient($request->get('id'));
        return $patient->getTraitements();
    }


    /**
     * Retourne l'Traitement du patient
     *
     * @param integer $patient
     * @param integer $id
     * @return PatientTraitement|null
     */
    private function getTraitement(int $patient, int $id): ?PatientTraitement
    {
        return $this->getRepository(PatientTraitement::class)->findOneBy(compact('patient', 'id'));
    }

    /**
     * @Rest\View(serializerGroups={"patient-traitement"})
     * @Rest\Get("/patients/{id}/traitements/{traitement}")
     */
    public function getTraitementAction(Request $request)
    {
        $patientTraitement = $this->getTraitement($request->get('id'), $request->get('traitement'));

        if ($patientTraitement) {
            return $patientTraitement;
        }

        return $this->notFound('Traitement avec ID ' . $request->get('traitement') . ' inexistant ou n\'est pas associé à votre compte');
    }


    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/patients/{id}/traitements/{traitement}")
     */
    public function deleteTraitementAction(Request $request)
    {
        $patientTraitement = $this->getTraitement($request->get('id'), $request->get('traitement'));

        $patient = $this->getPatient($request->get('id'));

        if ($patientTraitement) {
            $patient->removeTraitement($patientTraitement);
            $this->getManager()->flush();
            return new Response();
        }

        return $this->notFound('Traitement avec ID ' . $request->get('traitement') . ' inexistant ou n\'est pas associé à votre compte');
    }


    /**
     * @Rest\View(serializerGroups={"patient-traitement"}, statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/patients/{id}/traitements")
     */
    public function postTraitementAction(Request $request)
    {
        $patient = $this->getPatient($request->get('id'));

        $patientTraitement = new PatientTraitement();
        $patientTraitement->setPatient($patient);

        $form = $this->createForm(PatientTraitementsType::class, $patientTraitement, [
            'csrf_protection' => false,
             'date_format' => 'api'
        ]);

        


        $form->submit($this->formatValue($request->request->all()));

        if ($form->isValid()) {
            $em = $this->getManager();
            $em->persist($patientTraitement);
            $em->flush();
            return $patientTraitement;
        }

        return $form;
    }



    /**
     * @Rest\View(serializerGroups={"patient-traitement"})
     * @Rest\Put("/patients/{id}/traitements/{traitement}")
     */
    public function putTraitementAction(Request $request)
    {
        $patientTraitement = $this->getTraitement($request->get('id'), $request->get('traitement'));


        $form = $this->createForm(PatientTraitementsType::class, $patientTraitement, [
            'csrf_protection' => false,
             'date_format' => 'api'
        ]);


        $form->submit($this->formatValue($request->request->all()));

        if ($form->isValid()) {
            $em = $this->getManager();
            $em->flush();
            return $patientTraitement;
        }

        return $form;
    }
}
