<?php

namespace PS\ApiBundle\Controller\Patient\Consultation;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use PS\ApiBundle\Form\ConsultationAnalysesType;
use PS\GestionBundle\Entity\Consultation;
use PS\GestionBundle\Entity\ConsultationAnalyses;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AnalyseController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/patients/{id}/consultations/{consultation}/analyses")
     */
    public function getAnalysesAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        return $em->getRepository(ConsultationAnalyses::class)
            ->findByPatientConsultation(
                $request->get('patient')
                , $request->get('consultation')
            );
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/patients/{id}/consultations/{consultation}/analyses")
     */
    public function postAnalysesAction(Request $request)
    {
        $em           = $this->getDoctrine()->getEntityManager();
        $patient      = $this->getPatient($em, $request);
        $consultation = $this->getConsultation($em, $request);
        $analyse   = new ConsultationAnalyses();
        $form         = $this->createForm(ConsultationAnalysesType::class, $analyse);

        $consultation->setPatient($patient);
        $analyse->setConsultation($consultation);

        $form->handlRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $em->persist($analyse);
            $em->flush();
            return $analyse;
        }

        return $form;
    }

    /**
     * @Rest\View()
     * @Rest\Patch("/patients/{id}/consultations/{consultation}/analyses/{analyse}")
     */
    public function patchAnalysesAction(Request $request)
    {
        $em         = $this->getDoctrine()->getEntityManager();
        $analyse = $em->getAnalyse($em, $request);
        $form      = $this->createForm(ConsultationAnalysesType::class, $analyse);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();
            return $analyse;
        }

        return $form;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/patients/{id}/consultations/{consultation}/analyses/{analyse}")
     */
    public function deleteAnalysesAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $consultation = $this->getConsultation($em, $request);

        $analyse = $em->getRepository(ConsultationAnalyses::class)->find($request->get('analyse'));
        $consultation->removeAnalyse($analyse);
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
    private function getAnalyse($em, Request $request)
    {
        return $em->getRepository(ConsultationAnalyses::class)->find($request->get('analyse'));
    }
}
