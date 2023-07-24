<?php

namespace PS\ApiBundle\Controller\Patient;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use PS\ApiBundle\Form\PatientAssuranceType;
use PS\GestionBundle\Entity\Patient;
use PS\ParametreBundle\Entity\PatientAssurance;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Utilisation de la vue de FOSRestBundle

class AssuranceController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"patient-assurance"})
     * @Rest\Get("patients/{id}/assurances")
     */
    public function getAllAssuranceAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $assurances = $em->getRepository(PatientAssurance::class)->findByPatient($request->get('id'));
        if ($assurances) {
            return $assurances;
        }

        throw $this->createNotFoundException('Aucune assurance associée à ce patient');

    }
    /**
     * @Rest\View(serializerGroups={"patient-assurance"})
     * @Rest\Get("patients/{id}/assurances/{assurance}")
     */
    public function getAssuranceAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);

        if ($patient) {
            $assurance = $this->getAssurance($patient, $request);

            if ($assurance) {
                return $assurance;
            }

            throw $this->createAccessDeniedException('Vous n\'avez pas les permissions pour accéder à cette ressource');
        }

        throw $this->createNotFoundException('Patient introuvale');
    }

    /**
     * @Rest\View()
     * @Rest\Put("patients/{id}/assurances/{assurance}")
     */
    public function putAssuranceAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);

        if ($patient) {
            $assurance = $em->getRepository(PatientAssurance::class)
                ->findOneBy([
                    'id' => $request->get('assurance')
                    , 'patient' => $patient->getId(),
                ]);
            if ($assurance) {
                $form = $this->createForm(PatientAssuranceType::class, $assurance);
                $form->submit($request->request->all());

                if ($form->isValid()) {
                    $em->merge($assurance);
                    $em->flush();
                    return $assurance;
                }
                return $form;
            }

            throw $this->createAccessDeniedException('Vous n\'avez pas les permissions pour accéder à cette ressource');

        }

        throw $this->createNotFoundException('Patient inexistant');

    }

    /**
     * @param $patient
     * @param Request $request
     * @return mixed
     */
    private function getAssurance($patient, Request $request)
    {
         $em       = $this->getDoctrine()->getEntityManager();
        return $em->getRepository(PatientAssurance::class)
            ->findOneBy([
                'id' => $request->get('assurance')
                , 'patient' => $patient->getId(),
            ]);
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"patient-assurance"})
     * @Rest\Post("patients/{id}/assurances")
     */
    public function postAssuranceAction(Request $request)
    {
        $em        = $this->getDoctrine()->getEntityManager();
        $assurance = new PatientAssurance();
        $form      = $this->createForm(PatientAssuranceType::class, $assurance);
        $patient   = $this->getPatient($em, $request);

        if ($patient) {
            $assurance->setPatient($patient);

            $form->submit($request->request->all());

            if ($form->isValid()) {
                $em->persist($assurance);
                $em->flush();
                return $assurance;
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
     * @Rest\Delete("patients/{id}/assurances/{assurance}")
     */
    public function deleteAssuranceAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);

        if ($patient) {
            $assurance = $this->getAssurance($patient, $request);

            if (!$assurance) {
                throw $this->createAccessDeniedException('Vous n\'avez pas les permissions pour accéder à cette ressource');
            }

            $patient->removeAssurance($assurance);
            $em->flush();

        } else {
            throw $this->createNotFoundException('Patient inexistant');
        }

        
    }
}
