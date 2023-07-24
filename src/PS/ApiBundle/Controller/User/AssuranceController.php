<?php

namespace PS\ApiBundle\Controller\User;

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
     * @Rest\Get("users/{id}/assurances")
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
     * @Rest\Get("users/{id}/assurances/{assurance}")
     */
    public function getAssuranceAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $assurance = $em->getRepository(PatientAssurance::class)->findOneByPatient($request->get('id'), $request->get('assurance'));
        
        if ($assurance) {
            return $assurance;
        }
        
        throw $this->createNotFoundException('Assurance introuvable');
    }

    /**
     * @Rest\View(serializerGroups={"patient-assurance"})
     * @Rest\Patch("users/{id}/assurances/{assurance}")
     */
    public function patchAssuranceAction(Request $request)
    {
        $em        = $this->getDoctrine()->getEntityManager();
        $assurance = $em->getRepository(PatientAssurance::class)->find($request->get('assurance'));
        $form      = $this->createForm(PatientAssuranceType::class, $assurance);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $em->flush();
            return $assurance;
        }

        return $form;
    }

    /**
     * @Rest\View()
     * @Rest\Put("users/{id}/assurances/{assurance}")
     */
    public function putAssuranceAction(Request $request)
    {
        $em        = $this->getDoctrine()->getEntityManager();
        $assurance = $em->getRepository(PatientAssurance::class)->find($request->get('assurance'));
        $form      = $this->createForm(PatientAssuranceType::class, $assurance);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em->merge($assurance);
            $em->flush();
            return $assurance;
        }

        return $form;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"patient-assurance"})
     * @Rest\Post("users/{id}/assurances")
     */
    public function postAssuranceAction(Request $request)
    {
        $em        = $this->getDoctrine()->getEntityManager();
        $assurance = new PatientAssurance();
        $form      = $this->createForm(PatientAssuranceType::class, $assurance);
        $patient   = $this->getPatient($em, $request);

        $assurance->setPatient($patient);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em->persist($assurance);
            $em->flush();
            return $assurance;
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
     * @Rest\Delete("users/{id}/assurances/{assurance}")
     */
    public function deleteAssuranceAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);

        $assurance = $em->getRepository(PatientAssurance::class)->find($request->get('assurance'));
        $patient->removeAssurance($assurance);
        $em->flush();
    }
}
