<?php

namespace PS\ApiBundle\Controller\Patient;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use PS\ApiBundle\Form\AffectionType;
use PS\GestionBundle\Entity\Patient;
use PS\ParametreBundle\Entity\Affection;
use PS\ParametreBundle\Entity\PatientAffections;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Utilisation de la vue de FOSRestBundle

class AffectionController extends Controller
{

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
     * @Rest\View(serializerGroups={"patient-affection"})
     * @Rest\Get("patients/{id}/affections")
     */
    public function getAllAffectionAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $affections = $em->getRepository(PatientAffections::class)->findByPatient($request->get('id'));
        if ($affections) {
            return $affections;
        }

        throw $this->createNotFoundException('Aucune affection pour ce patient');

    }

    /**
     * @Rest\View(serializerGroups={"patient-affection"})
     * @Rest\Get("patients/{id}/affections/{affection}")
     */
    public function getAffectionAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);

        if ($patient) {
            $affection = $this->getAffection($patient, $request);

            if ($affection) {
                return $affection;
            }

            throw $this->createAccessDeniedException('Vous n\'avez pas les permissions pour accéder à cette ressource');
        }

        throw $this->createNotFoundException('Patient introuvale');
    }

    /**
     * @Rest\View(serializerGroups={"patient-affection"}, statusCode=Response::HTTP_CREATED)
     * @Rest\Post("patients/{id}/affections")
     *
     * @return View
     */
    public function postAffectionAction(Request $request)
    {

        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);

        if ($patient) {
            $affection = new PatientAffections();
            $affection->setPatient($patient);
            $form = $this->createForm(AffectionType::class, $affection);
            $form->submit($request->request->all());

            if ($form->isValid()) {

                $em->persist($affection);
                $em->flush();

                return $affection;
            }

            return $form;
        }

        throw $this->createNotFoundException('Patient inexistant');

    }

    /**
     * @param $patient
     * @param Request $request
     * @return mixed
     */
    private function getAffection(Patient $patient, Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        return $em->getRepository(PatientAffections::class)
            ->findOneBy([
                'id' => $request->get('affection')
                , 'patient' => $patient->getId(),
            ]);
    }

    /**
     * @Rest\View(serializerGroups={"patient-affection"})
     * @Rest\Put("/patients/{id}/affections/{affection}")
     */
    public function putAffectionAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);

        if ($patient) {
            $affection = $this->getAffection($patient, $request);

            if ($affection) {
                $form = $this->createForm(AffectionType::class, $affection);
                $form->submit($request->request->all());

                if ($form->isValid()) {
                    $em->merge($affection);
                    $em->flush();
                    return $affection;
                }

                return $form;
            }

            throw $this->createAccessDeniedException('Vous n\'avez pas les permissions pour accéder à cette ressource');

        }

        throw $this->createNotFoundException('Patient inexistant');

    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/patients/{id}/affections/{affection}")
     */
    public function deleteAffectionAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);

        if ($patient) {
            $affection = $affection = $this->getAffection($patient, $request);

            if (!$affection) {
                throw $this->createAccessDeniedException('Vous n\'avez pas les permissions pour accéder à cette ressource');
            }

            $patient->removeAffection($affection);
            $em->flush();
        } else {
             throw $this->createNotFoundException('Patient inexistant');
        }

       

    }
}
