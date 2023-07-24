<?php

namespace PS\ApiBundle\Controller\Patient;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use PS\ApiBundle\Form\PatientVaccinType;
use PS\GestionBundle\Entity\Patient;
use PS\ParametreBundle\Entity\PatientVaccin;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Utilisation de la vue de FOSRestBundle

class VaccinationController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"patient-vaccin"})
     * @Rest\Get("patients/{id}/vaccins")
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
     * @Rest\Get("patients/{id}/vaccins/{vaccin}")
     */
    public function getVaccinAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);

        if ($patient) {
            $vaccin = $this->getVaccin($patient, $request);

            if ($vaccin) {
                return $vaccin;
            }

            throw $this->createAccessDeniedException('Vous n\'avez pas les permissions pour accéder à cette ressource');
        }

        throw $this->createNotFoundException('Patient introuvale');

    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED,serializerGroups={"patient-vaccin"})
     * @Rest\Post("patients/{id}/vaccins")
     */
    public function postVaccinAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $vaccin  = new PatientVaccin();
        $form    = $this->createForm(PatientVaccinType::class, $vaccin);
        $patient = $this->getPatient($em, $request);

        if ($patient) {
            $vaccin->setPatient($patient);

            $form->submit($request->request->all());

            if ($form->isValid()) {
                $em->persist($vaccin);
                $em->flush();
                return $vaccin;
            }

            return $form;
        }

        throw $this->createNotFoundException('Patient inexistant');

    }

    /**
     * @Rest\View(serializerGroups={"patient-vaccin"})
     * @Rest\Put("patients/{id}/vaccins/{vaccin}")
     */
    public function putVaccinAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);

        if ($patient) {
            $vaccin = $this->getVaccin($patient, $request);
            if ($vaccin) {
                $form = $this->createForm(PatientVaccinType::class, $vaccin);
                $form->submit($request->request->all());

                if ($form->isValid()) {
                    $em->merge($vaccin);
                    $em->flush();
                    return $vaccin;
                }
                return $form;
            }

            throw $this->createAccessDeniedException('Vous n\'avez pas les permissions pour accéder à cette ressource');

        }

        throw $this->createNotFoundException('Patient inexistant');
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
     * @param $patient
     * @param Request $request
     * @return mixed
     */
    private function getVaccin($patient, Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        return $em->getRepository(PatientVaccin::class)
            ->findOneBy([
                'id' => $request->get('vaccin')
                , 'patient' => $patient->getId(),
            ]);
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("patients/{id}/vaccins/{vaccin}")
     */
    public function deleteVaccinAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);

        if ($patient) {
            $vaccin = $this->getVaccin($patient, $request);

            if (!$vaccin) {
                throw $this->createAccessDeniedException('Vous n\'avez pas les permissions pour accéder à cette ressource');
            }

            $patient->removeVaccination($vaccin);
            $em->flush();
        } else {
            throw $this->createNotFoundException('Patient inexistant');
        }

        

    }
}
