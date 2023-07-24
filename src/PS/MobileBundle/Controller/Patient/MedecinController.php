<?php

namespace PS\MobileBundle\Controller\Patient;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PS\MobileBundle\Controller\ApiTrait;
use PS\ParametreBundle\Entity\PatientMedecin;
use PS\ParametreBundle\Form\PatientMedecinType;

class MedecinController extends Controller
{
    use ApiTrait;

    /**
     * @Rest\View(serializerGroups={"patient-medecin", "specialite"})
     * @Rest\Get("/patients/{id}/medecins")
     */
    public function getMedecinsAction(Request $request)
    {
        $patient = $this->getPatient($request->get('id'));
        return $patient->getMedecins();
    }

    /**
     * Retourne l'Medecin du patient
     *
     * @param integer $patient
     * @param integer $id
     * @return PatientMedecin|null
     */
    private function getMedecin(int $patient, int $id): ?PatientMedecin
    {
        return $this->getRepository(PatientMedecin::class)->findOneBy(compact('patient', 'id'));
    }

    /**
     * @Rest\View(serializerGroups={"patient-medecin", "specialite"})
     * @Rest\Get("/patients/{id}/medecins/{medecin}")
     */
    public function getMedecinAction(Request $request)
    {
        $patientMedecin = $this->getMedecin($request->get('id'), $request->get('medecin'));

        if ($patientMedecin) {
            return $patientMedecin;
        }

        return $this->notFound('Medecin avec ID ' . $request->get('medecin') . ' inexistant ou n\'est pas associé à votre compte');
    }


    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/patients/{id}/medecins/{medecin}")
     */
    public function deleteMedecinAction(Request $request)
    {
        $patientMedecin = $this->getMedecin($request->get('id'), $request->get('medecin'));

        $patient = $this->getPatient($request->get('id'));

        if ($patientMedecin) {
            $patient->removeMedecin($patientMedecin);
            $this->getManager()->flush();
            return new Response();
        }

        return $this->notFound('Medecin avec ID ' . $request->get('medecin') . ' inexistante ou n\'est pas associé à votre compte');
    }


    /**
     * @Rest\View(serializerGroups={"patient-medecin", "specialite"}, statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/patients/{id}/medecins")
     */
    public function postMedecinAction(Request $request)
    {
        $patient = $this->getPatient($request->get('id'));

        $patientMedecin = new PatientMedecin();
        $patientMedecin->setPatient($patient);

        $form = $this->createForm(PatientMedecinType::class, $patientMedecin, [
            'csrf_protection' => false
        ]);

         $data = $this->formatValue($request->request->all());


        $form->submit($data);

        if ($form->isValid()) {
            $em = $this->getManager();
            $em->persist($patientMedecin);
            $em->flush();
            return $patientMedecin;
        }

        return $form;
    }



    /**
     * @Rest\View(serializerGroups={"patient-medecin", "specialite"})
     * @Rest\Put("/patients/{id}/medecins/{medecin}")
     */
    public function putMedecinAction(Request $request)
    {
        $patientMedecin = $this->getMedecin($request->get('id'), $request->get('medecin'));


        $form = $this->createForm(PatientMedecinType::class, $patientMedecin, [
            'csrf_protection' => false
        ]);

         $data = $this->formatValue($request->request->all());


        $form->submit($data);

        if ($form->isValid()) {
            $em = $this->getManager();
            $em->flush();
            return $patientMedecin;
        }

        return $form;
    }
}
