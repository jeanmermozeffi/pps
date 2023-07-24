<?php

namespace PS\ApiBundle\Controller\Patient;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PS\GestionBundle\Entity\Consultation;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Entity\Medecin;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher; 

class ConsultationController extends Controller
{
    /**
     * @Rest\View()
     * @QueryParam(name="offset", requirements="\d+", default="", description="Index de début de la pagination")
     * @QueryParam(name="limit", requirements="\d+", default="10", description="Index de fin de la pagination")
     * @Rest\Get("patients/{id}/consultations")
     */
    public function getConsultationsAction(Request $request, ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $limit = $paramFetcher->get('limit');
        $offset = $paramFetcher->get('offset');

        $consultations = $em->getRepository(Consultation::class)
            ->findByPatient(
                $request->get('id')
                , $limit
                , $offset
            );
        if ($consultations) {
            return $consultations;
        }

        throw $this->createNotFoundException('Aucune consultation n\'est associé à ce patient');
    }

    /**
     * @Rest\View()
     * @Rest\Get("patients/{id}/consultations/{consultation}")
     */
    public function getConsultationAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $consultations = $em->getRepository(Consultation::class)->find($request->get('consultation'));
        if ($consultations) {
            return $consultations;
        }

        return $this->createNotFoundException('Aucune consultation n\'est associé à cet ID');
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("patients/{id}/consultations")
     */
    public function postConsultationAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $consultation = new Consultation();
        $form = $this->createForm(ConsultationType::class, $consultation);
        $medecin = $em->getRepository(Medecin::class)->findPersoByParam($this->getUser()->getPersonne()->getId());
        $patient = $em->getRepository(Patient::class)->find($request->get('id'));

        $consultation->setPatient($patient);
        $consultation->setMedecin($medecin[0]);

        $form->handlRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($consultation);
            $em->flush();
            return $consultation;
        }

        return $form;
    }


    /**
     * @Rest\View()
     * @Rest\Put("patients/{id}/consultations/{consultation}")
     */
    public function putConsultationAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $consultation = $em->getRepository(Consultation::class)->get($request->get('consultation'));
        $form = $this->createForm(ConsultationType::class, $consultation);
       
        $patient = $em->getRepository(Patient::class)->find($request->get('id'));

        $form->handlRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $consultation;
        }

        return $form;
    }


     /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("patients/{id}/consultations/{consultation}")
     */
    public function deleteConsultationAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $consultation = $em->getRepository(Consultation::class)->find($request->get('consultation'));
    
        $em->remove($consultation);

        $em->flush();
    }
}
