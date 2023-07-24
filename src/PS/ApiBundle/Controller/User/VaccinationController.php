<?php

namespace PS\ApiBundle\Controller\User;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use PS\GestionBundle\Entity\Patient;
use PS\ParametreBundle\Entity\PatientVaccin;
use PS\ApiBundle\Form\PatientVaccinType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Utilisation de la vue de FOSRestBundle

class VaccinationController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"patient-vaccin"})
     * @Rest\Get("users/{id}/vaccins")
     */
    public function getAllVaccinAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $vaccins = $em->getRepository(PatientVaccin::class)->findByPatient($request->get('id'));
        if ($vaccins) {
            return $vaccins;
        }

        throw $this->createNotFoundException('Aucun vaccin associé à ce patient');

    }

    /**
     * @Rest\View(serializerGroups={"patient-vaccin"})
     * @Rest\Get("users/{id}/vaccins/{vaccin}")
     */
    public function getVaccinAction(Request $request)
    {
        $em     = $this->getDoctrine()->getEntityManager();
        $vaccin = $em->getRepository(PatientVaccin::class)->findOneByPatient($request->get('id'), $request->get('vaccin'));

        if ($vaccin) {
            return $vaccin;
        }

        throw $this->createNotFoundException('Vaccn introuvale');

    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED,serializerGroups={"patient-vaccin"})
     * @Rest\Post("users/{id}/vaccins")
     */
    public function postVaccinAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $vaccin  = new PatientVaccin();
        $form    = $this->createForm(PatientVaccinType::class, $vaccin);
        $patient = $this->getPatient($em, $request);

        $vaccin->setPatient($patient);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em->persist($vaccin);
            $em->flush();
            return $vaccin;
        }

        return $form;
    }

    /**
     * @Rest\View(serializerGroups={"patient-vaccin"})
     * @Rest\Patch("users/{id}/vaccins/{vaccin}")
     */
    public function patchVaccinAction(Request $request)
    {
        $em     = $this->getDoctrine()->getEntityManager();
        $vaccin = $em->getRepository(PatientVaccin::class)->find($request->get('vaccin'));
        $form   = $this->createForm(PatientVaccinType::class, $vaccin);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $em->flush();
            return $vaccin;
        }

        return $form;
    }


    /**
     * @Rest\View(serializerGroups={"patient-vaccin"})
     * @Rest\Put("users/{id}/vaccins/{vaccin}")
     */
    public function putVaccinAction(Request $request)
    {
        $em     = $this->getDoctrine()->getEntityManager();
        $vaccin = $em->getRepository(PatientVaccin::class)->find($request->get('vaccin'));
        $form   = $this->createForm(PatientVaccinType::class, $vaccin);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em->merge($vaccin);
            $em->flush();
            return $vaccin;
        }

        return $form;
    }

    /**
     * Retourne le patient actuel
     * @param $em
     * @param $id
     * @return mixed
     */
    private function getPatient($em, Request $request)
    {
        return $em->getRepository(Patient::class)->find($request->get('id'));
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("users/{id}/vaccins/{vaccin}")
     */
    public function deleteVaccinAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);

        $vaccin = $em->getRepository(PatientVaccin::class)->find($request->get('vaccin'));
        $patient->removeVaccination($vaccin);
        $em->flush();
    }
}
