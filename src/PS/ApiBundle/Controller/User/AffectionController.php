<?php

namespace PS\ApiBundle\Controller\User;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use PS\ApiBundle\Form\AffectionType;
use PS\GestionBundle\Entity\Patient;
use PS\ParametreBundle\Entity\Affection;
use PS\ParametreBundle\Entity\PatientAffections;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $userManager = $this->get('fos_user.user_manager');
        $em          = $this->getDoctrine()->getEntityManager();
        $user        = $userManager->findUserBy(['id' => $request->get('id')]);
        return $em->getRepository(Patient::class)->find($request->get('id'));
    }

    /**
     * @Rest\View(serializerGroups={"patient-affection"})
     * @Rest\Get("users/{id}/affections")
     */
    public function getAllAffectionAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
       
        $affections = $em->getRepository(PatientAffections::class)->findByPatient($request->get('id'));
        if ($affections) {
            return $affections;
        }

        return $this->createNotFoundException('Aucune affection pour ce patient');

    }

    /**
     * @Rest\View(serializerGroups={"patient-affection"})
     * @Rest\Get("users/{id}/affections/{affection}")
     */
    public function getAffectionAction(Request $request)
    {
        $em        = $this->getDoctrine()->getEntityManager();
        $affection = $em->getRepository(PatientAffections::class)->findOneByPatient($request->get('id'), $request->get('affection'));

        if ($affection) {
            return $affection;
        }

        throw $this->createNotFoundException('Affection introuvale');
    }

    /**
     * @Rest\View(serializerGroups={"patient-affection"}, statusCode=Response::HTTP_CREATED)
     * @Rest\Post("users/{id}/affections")
     *
     * @return View
     */
    public function postAffectionAction(Request $request)
    {

        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $em->getRepository(Patient::class)->find($request->get('id'));

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

        return new JsonResponse(['message' => 'Cette affection est inexistante pour ce patient'], Response::HTTP_NOT_FOUND);

    }

    /**
     * @Rest\View(serializerGroups={"patient-affection"})
     * @Rest\Patch("users/{id}/affections/{affection}")
     */
    public function patchAffectionAction(Request $request)
    {
        $em        = $this->getDoctrine()->getEntityManager();
        $affection = $em->getRepository(PatientAffections::class)->find($request->get('affection'));
        $form      = $this->createForm(AffectionType::class, $affection);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $em->flush();
            return $affection;
        }

        return $form;
    }

    /**
     * @Rest\View(serializerGroups={"patient-affection"})
     * @Rest\Put("/users/{id}/affections/{affection}")
     */
    public function putAffectionAction(Request $request)
    {
        $em        = $this->getDoctrine()->getEntityManager();
        $affection = $em->getRepository(PatientAffections::class)->find($request->get('affection'));
        $form      = $this->createForm(AffectionType::class, $affection);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em->merge($affection);
            $em->flush();
            return $affection;
        }

        return $form;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/users/{id}/affections/{affection}")
     */
    public function deleteAffectionAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);

        $affection = $em->getRepository(PatientAffections::class)->find($request->get('affection'));
        $patient->removeAffection($affection);
        $em->flush();
    }
}
