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
use PS\ParametreBundle\Entity\PatientVaccin;
use PS\ParametreBundle\Form\PatientVaccinType;

class VaccinationController extends Controller
{
    use ApiTrait;

    /**
     * @Rest\View(serializerGroups={"patient-vaccination"})
     * @Rest\Get("/patients/{id}/vaccins")
     */
    public function getVaccinationsAction(Request $request)
    {
        $patient = $this->getPatient($request->get('id'));
        return $patient->getVaccinations();
    }


    /**
     * Retourne l'Vaccination du patient
     *
     * @param integer $patient
     * @param integer $id
     * @return PatientVaccin|null
     */
    private function getVaccination(int $patient, int $id): ?PatientVaccin
    {
        return $this->getRepository(PatientVaccin::class)->findOneBy(compact('patient', 'id'));
    }

    /**
     * @Rest\View(serializerGroups={"patient-vaccination"})
     * @Rest\Get("/patients/{id}/vaccins/{vaccination}")
     */
    public function getVaccinationAction(Request $request)
    {
        $patientVaccin = $this->getVaccination($request->get('id'), $request->get('vaccination'));

        if ($patientVaccin) {
            return $patientVaccin;
        }

        return $this->notFound('Vaccin avec ID ' . $request->get('vaccination') . ' inexistant ou n\'est pas associé à votre compte');
    }


    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/patients/{id}/vaccins/{vaccination}")
     */
    public function deleteVaccinationAction(Request $request)
    {
        $patientVaccin = $this->getVaccination($request->get('id'), $request->get('vaccination'));

        $patient = $this->getPatient($request->get('id'));

        if ($patientVaccin) {
            $patient->removeVaccination($patientVaccin);
            $this->getManager()->flush();
            return new Response();
        }

        return $this->notFound('Vaccin avec ID ' . $request->get('vaccination') . ' inexistant ou n\'est pas associé à votre compte');
    }


    /**
     * @Rest\View(serializerGroups={"patient-vaccination"}, statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/patients/{id}/vaccins")
     */
    public function postVaccinationAction(Request $request)
    {
        $patient = $this->getPatient($request->get('id'));

        $patientVaccin = new PatientVaccin();
        $patientVaccin->setPatient($patient);

        $form = $this->createForm(PatientVaccinType::class, $patientVaccin, [
            'csrf_protection' => false,
             'date_format' => 'api'
        ]);


        $form->submit($this->formatValue($request->request->all()));

        if ($form->isValid()) {
            $em = $this->getManager();
            $em->persist($patientVaccin);
            $em->flush();
            return $patientVaccin;
        }

        return $form;
    }



    /**
     * @Rest\View(serializerGroups={"patient-vaccination"})
     * @Rest\Put("/patients/{id}/vaccins/{vaccination}")
     */
    public function putVaccinationAction(Request $request)
    {
        $patientVaccin = $this->getVaccination($request->get('id'), $request->get('vaccination'));


        $form = $this->createForm(PatientVaccinType::class, $patientVaccin, [
            'csrf_protection' => false,
             'date_format' => 'api'
        ]);


        $form->submit($this->formatValue($request->request->all()));

        if ($form->isValid()) {
            $em = $this->getManager();
            $em->flush();
            return $patientVaccin;
        }

        return $form;
    }

}
