<?php

namespace PS\ApiBundle\Controller\Patient;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use PS\ApiBundle\Form\PatientAllergiesType;
use PS\GestionBundle\Entity\Patient;
use PS\ParametreBundle\Entity\PatientAllergies;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Utilisation de la vue de FOSRestBundle

class AllergieController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"patient-allergie"})
     * @Rest\Get("patients/{id}/allergies")
     */
    public function getAllAllergieAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $allergies = $em->getRepository(PatientAllergies::class)->findByPatient($request->get('id'));
        if ($allergies) {
            return $allergies;
        }

        throw $this->createNotFoundException('Aucune Allergie connue pour ce  patient');

    }

    /**
     * @Rest\View(serializerGroups={"patient-allergie"})
     * @Rest\Get("patients/{id}/allergies/{allergie}")
     */
    public function getAllergieAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);
        if ($patient) {
            $allergie = $this->getAllergie($patient, $request);

            if ($allergie) {
                return $allergie;
            }

            throw $this->createAccessDeniedException('Vous n\'avez pas les permissions pour accéder à cette ressource');
        }

        throw $this->createNotFoundException('Patient introuvale');

    }

    /**
     * @param $patient
     * @param Request $request
     * @return mixed
     */
    private function getAllergie(Patient $patient, Request $request)
    {
         $em       = $this->getDoctrine()->getEntityManager();
        return $em->getRepository(PatientAllergies::class)
            ->findOneBy([
                'id' => $request->get('allergie')
                , 'patient' => $patient->getId(),
            ]);
    }

    /**
     * @Rest\View(serializerGroups={"patient-allergie"})
     * @Rest\Patch("patients/{id}/allergies/{allergie}")
     */
    public function patchAllergieAction(Request $request)
    {
        $em       = $this->getDoctrine()->getEntityManager();
        $allergie = $em->getRepository(PatientAllergies::class)->find($request->get('allergie'));
        $form     = $this->createForm(PatientAllergiesType::class, $allergie);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $em->merge($allergie);
            $em->flush();
            return $allergie;
        }

        return $form;
    }

    /**
     * @Rest\View(serializerGroups={"patient-allergie"})
     * @Rest\Put("patients/{id}/allergies/{allergie}")
     */
    public function putAllergieAction(Request $request)
    {
        $em       = $this->getDoctrine()->getEntityManager();
        $allergie = $em->getRepository(PatientAllergies::class)->find($request->get('allergie'));
        $form     = $this->createForm(PatientAllergiesType::class, $allergie);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em->merge($allergie);
            $em->flush();
            return $allergie;
        }

        return $form;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"patient-allergie"})
     * @Rest\Post("patients/{id}/allergies")
     */
    public function postAllergieAction(Request $request)
    {
        $em       = $this->getDoctrine()->getEntityManager();
        $allergie = new PatientAllergies();
        $form     = $this->createForm(PatientAllergiesType::class, $allergie);
        $patient  = $this->getPatient($em, $request);

        if ($patient) {
            $allergie->setPatient($patient);

            $form->submit($request->request->all());

            if ($form->isValid()) {
                $em->persist($allergie);
                $em->flush();
                return $allergie;
            }

            return $form;
        }

        throw $this->createNotFoundException('Patient inexistant');

    }

    /**
     * Retourne le patient actuel
     * @param $em
     * @param Request $request
     * @return mixed
     */
    private function getPatient($em, Request $request)
    {
        return $em->getRepository(Patient::class)->find($request->get('id'));
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("patients/{id}/allergies/{allergie}")
     */
    public function deleteAllergieAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);

        $allergie = $this->getAllergie($patient, $request);

        if (!$allergie) {
            throw $this->createAccessDeniedException('Vous n\'avez pas les permissions pour accéder à cette ressource');
        }

        $patient->removeAllergy($allergie);
        $em->flush();
    }

}
