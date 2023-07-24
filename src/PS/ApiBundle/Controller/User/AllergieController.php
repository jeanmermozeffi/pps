<?php

namespace PS\ApiBundle\Controller\User;

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
     * @Rest\View()
     * @Rest\Get("users/{id}/allergies")
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
     * @Rest\View()
     * @Rest\Get("users/{id}/allergies/{allergie}")
     */
    public function getAllergieAction(Request $request)
    {
        $em       = $this->getDoctrine()->getEntityManager();
        $allergie = $em->getRepository(PatientAllergies::class)->findOneByPatient($request->get('id'), $request->get('allergie'));

        if ($allergie) {
            return $allergie;
        }

        throw $this->createNotFoundException('Vaccin introuvale');

    }

    /**
     * @Rest\View()
     * @Rest\Patch("users/{id}/allergies/{allergie}")
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
     * @Rest\View()
     * @Rest\Put("users/{id}/allergies/{allergie}")
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
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("users/{id}/allergies")
     */
    public function postAllergieAction(Request $request)
    {
        $em       = $this->getDoctrine()->getEntityManager();
        $allergie = new PatientAllergies();
        $form     = $this->createForm(PatientAllergiesType::class, $allergie);
        $patient  = $this->getPatient($em, $request);

        $allergie->setPatient($patient);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em->persist($allergie);
            $em->flush();
            return $allergie;
        }

        return $form;
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
     * @Rest\Delete("users/{id}/allergies/{allergie}")
     */
    public function deleteAllergieAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);

        $allergie = $em->getRepository(PatientAllergies::class)->find($request->get('allergie'));
        $patient->removeAllergy($allergie);
        $em->flush();
    }

}
