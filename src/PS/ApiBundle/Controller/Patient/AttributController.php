<?php

namespace PS\ApiBundle\Controller\Patient;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use PS\ApiBundle\Form\AttributType;
use PS\GestionBundle\Entity\Patient;
use PS\ParametreBundle\Entity\Attribut;
use PS\ParametreBundle\Entity\LigneAttribut;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Utilisation de la vue de FOSRestBundle

class AttributController extends Controller
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
     * @Rest\View(serializerGroups={"patient-attribut", "attribut"}, statusCode=Response::HTTP_CREATED)
     * @Rest\Patch("patients/{id}/attributs")
     *
     * @return View
     */
    public function patchAffectionAction(Request $request)
    {

        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);

        if ($patient) {
            $oldAttributs = $patient->getLignesAttributs();

            foreach ($oldAttributs as $attribut) {
                $patient->removeLignesAttribut($attribut);
            }

            $form = $this->createForm(AttributType::class, $patient);
            $form->submit($request->request->all(), false);

            

            //return $attribut;

            if ($form->isValid()) {

                //$em->persist($patient);

                //$patient->setAttribut($attribut);
                $em->flush();

                return $patient->getLignesAttributs()->toArray();
            }

            return $form;
        }

        throw $this->createNotFoundException('Patient inexistant');

    }

    /**
     * @Rest\View(serializerGroups={"patient-attribut", "attributs"})
     * @Rest\Get("patients/{id}/attributs")
     */
    public function getAllAttributAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $atributs = $em->getRepository(LigneAttribut::class)->findByPatient($request->get('id'));
        if ($atributs) {
            return $atributs;
        }
    }

}
