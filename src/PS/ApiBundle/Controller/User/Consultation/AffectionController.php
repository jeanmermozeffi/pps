<?php

namespace PS\ApiBundle\Controller\User\Consultation;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use PS\ApiBundle\Form\ConsultationAffectionsType;
use PS\GestionBundle\Entity\Consultation;
use PS\GestionBundle\Entity\ConsultationAffections;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AffectionController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("users/{id}/consultations/{consultation}/affections")
     */
    public function getAffectionsAction(Request $request)
    {
        
        $em = $this->getDoctrine()->getEntityManager();
        $data = $em->getRepository(ConsultationAffections::class)
            ->findByPatientConsultation(
                $request->get('patient')
                , $request->get('consultation')
            );
        return $data;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("users/{id}/consultations/{consultation}/affections")
     */
    public function postAffectionsAction(Request $request)
    {
        $em           = $this->getDoctrine()->getEntityManager();
        $patient      = $this->getPatient($em, $request);
        $consultation = $this->getConsultation($em, $request);
        $affection   = new ConsultationAffections();
        $form         = $this->createForm(ConsultationAffectionsType::class, $affection);

        $consultation->setPatient($patient);
        $affection->setConsultation($consultation);

        $form->handlRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $em->persist($affection);
            $em->flush();
            return $affection;
        }

        return $form;
    }

    /**
     * @Rest\View()
     * @Rest\Patch("users/{id}/consultations/{consultation}/affections/{affection}")
     */
    public function patchAffectionsAction(Request $request)
    {
        $em         = $this->getDoctrine()->getEntityManager();
        $affection = $em->getAffection($em, $request);
        $form      = $this->createForm(ConsultationAffectionsType::class, $affection);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();
            return $affection;
        }

        return $form;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("users/{id}/consultations/{consultation}/affections/{afection}")
     */
    public function deleteAffectionsAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $consultation = $this->getConsultation($em, $request);

        $affection = $em->getRepository(ConsultationAffections::class)->find($request->get('affection'));
        $consultation->removeAffection($affection);
        $em->flush();
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
     * @param $em
     * @param Request $request
     * @return mixed
     */
    private function getConsultation($em, Request $request)
    {
        return $em->getRepository(Consultation::class)->find($request->get('consultation'));
    }

    /**
     * @param $em
     * @param Request $request
     * @return mixed
     */
    private function getAffection($em, Request $request)
    {
        return $em->getRepository(ConsultationAffections::class)->find($request->get('affection'));
    }  
}
