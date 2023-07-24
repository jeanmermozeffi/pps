<?php

namespace PS\ApiBundle\Controller\Patient;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use PS\ApiBundle\Form\PatientMedecinType;
use PS\GestionBundle\Entity\Patient;
use PS\ParametreBundle\Entity\PatientMedecin;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Utilisation de la vue de FOSRestBundle

class MedecinController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"patient-medecin", "specialite"})
     * @Rest\Get("patients/{id}/medecins")
     */
    public function getAllMedecinAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $medecins = $em->getRepository(PatientMedecin::class)->findByPatient($request->get('id'));
        if ($medecins) {
            return $medecins;
        }

        throw $this->createNotFoundException('Aucune medecin associée à ce patient');

    }
    /**
     * @Rest\View(serializerGroups={"patient-medecin", "specialite"})
     * @Rest\Get("patients/{id}/medecins/{medecin}")
     */
    public function getMedecinAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);

        if ($patient) {
            $medecin = $this->getMedecin($patient, $request);

            if ($medecin) {
                return $medecin;
            }

            throw $this->createAccessDeniedException('Vous n\'avez pas les permissions pour accéder à cette ressource');
        }

        throw $this->createNotFoundException('Patient introuvale');
    }

    /**
     * @Rest\View(serializerGroups={"patient-medecin", "specialite"})
     * @Rest\Put("patients/{id}/medecins/{medecin}")
     */
    public function putMedecinAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);

        if ($patient) {
            $medecin = $em->getRepository(PatientMedecin::class)
                ->findOneBy([
                    'id' => $request->get('medecin')
                    , 'patient' => $patient->getId(),
                ]);
            if ($medecin) {
                $form = $this->createForm(PatientMedecinType::class, $medecin);
                $form->submit($request->request->all());

                if ($form->isValid()) {
                    $em->merge($medecin);
                    $em->flush();
                    return $medecin;
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
    private function getMedecin($patient, Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        return $em->getRepository(PatientMedecin::class)
            ->findOneBy([
                'id' => $request->get('medecin')
                , 'patient' => $patient->getId(),
            ]);
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"patient-medecin","specialite"})
     * @Rest\Post("patients/{id}/medecins")
     */
    public function postMedecinAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $medecin = new PatientMedecin();
        $form    = $this->createForm(PatientMedecinType::class, $medecin);
        $patient = $this->getPatient($em, $request);

        if ($patient) {
            $medecin->setPatient($patient);

            $form->submit($request->request->all());

            if ($form->isValid()) {
                $em->persist($medecin);
                $em->flush();
                return $medecin;
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
     * @Rest\Delete("patients/{id}/medecins/{medecin}")
     */
    public function deleteMedecinAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);

        if ($patient) {
            $medecin = $this->getMedecin($patient, $request);

            if (!$medecin) {
                throw $this->createAccessDeniedException('Vous n\'avez pas les permissions pour accéder à cette ressource');
            }

            $patient->removeMedecin($medecin);
            $em->flush();

        } else {
            throw $this->createNotFoundException('Patient inexistant');
        }

    }
}
