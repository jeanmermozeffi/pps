<?php

namespace PS\MobileBundle\Controller\Patient;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PS\MobileBundle\Controller\ApiTrait;
use PS\ParametreBundle\Entity\PatientAllergies;
use PS\ParametreBundle\Form\PatientAllergiesType;

class AllergieController extends Controller
{
    use ApiTrait;

    /**
     * @Rest\View(serializerGroups={"patient-allergie"})
     * @Rest\Get("/patients/{id}/allergies")
     */
    public function getAllergiesAction(Request $request)
    {
        $patient = $this->getPatient($request->get('id'));
        return $patient->getAllergies();
    }

    /**
     * Retourne l'allergie du patient
     *
     * @param integer $patient
     * @param integer $id
     * @return PatientAllergies|null
     */
    private function getAllergie(int $patient, int $id): ?PatientAllergies
    {
        return $this->getRepository(PatientAllergies::class)->findOneBy(compact('patient', 'id'));
    }

    /**
     * @Rest\View(serializerGroups={"patient-allergie"})
     * @Rest\Get("/patients/{id}/allergies/{allergie}")
     */
    public function getAllergieAction(Request $request)
    {
        $patientAllergie = $this->getAllergie($request->get('id'), $request->get('allergie'));

        if ($patientAllergie) {
            return $patientAllergie;
        }

        return $this->notFound('Allergie avec ID '.$request->get('allergie').' inexistant ou n\'est pas associé à votre compte');
    }


     /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/patients/{id}/allergies/{allergie}")
     */
    public function deleteAllergieAction(Request $request)
    {
        $patientAllergie = $this->getAllergie($request->get('id'), $request->get('allergie'));

        $patient = $this->getPatient($request->get('id'));

        if ($patientAllergie) {
            $patient->removeAllergy($patientAllergie);
            $this->getManager()->flush();
            return new Response();
        }

        return $this->notFound('Allergie avec ID '.$request->get('allergie').' inexistante ou n\'est pas associée à votre compte');
    }


     /**
     * @Rest\View(serializerGroups={"patient-allergie"}, statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/patients/{id}/allergies")
     */
    public function postAllergieAction(Request $request)
    {
        $patient = $this->getPatient( $request->get('id'));

        $patientAllergie = new PatientAllergies();
        $patientAllergie->setPatient($patient);

        $form = $this->createForm(PatientAllergiesType::class, $patientAllergie, [
            'csrf_protection' => false
        ]);

        $data = $this->formatValue($request->request->all());


        $form->submit($data);

        if ($form->isValid()) {
            $em = $this->getManager();
            $em->persist($patientAllergie);
            $em->flush();
            return $patientAllergie;
        }

        return $form;
    }



     /**
     * @Rest\View(serializerGroups={"patient-allergie"})
     * @Rest\Put("/patients/{id}/allergies/{allergie}")
     */
    public function putAllergieAction(Request $request)
    {
    
        $patientAllergie =$this->getAllergie( $request->get('id'), $request->get('allergie') );
       

        $form = $this->createForm(PatientAllergiesType::class, $patientAllergie, [
            'csrf_protection' => false
        ]);

        $data = $this->formatValue($request->request->all());


        $form->submit($data);

        if ($form->isValid()) {
            $em = $this->getManager();
            $em->flush();
            return $patientAllergie;
        }

        return $form;
    }
}
