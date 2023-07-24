<?php

namespace PS\ApiBundle\Controller\Patient\Consultation;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use PS\ApiBundle\Form\ConsultationTraitementsType;
use PS\GestionBundle\Entity\Consultation;
use PS\GestionBundle\Entity\ConsultationTraitements;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MedicamentController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/patients/{id}/consultations/{consultation}/medicaments")
     */
    public function getMedicamentsAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        return $em->getRepository(ConsultationTraitements::class)
            ->findByPatientConsultation(
                $request->get('patient')
                , $request->get('consultation')
            );
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/patients/{id}/consultations/{consultation}/medicaments")
     */
    public function postMedicamentsAction(Request $request)
    {
        $em           = $this->getDoctrine()->getEntityManager();
        $patient      = $this->getPatient($em, $request);
        $consultation = $this->getConsultation($em, $request);
        $medicament   = new ConsultationTraitements();
        $form         = $this->createForm(ConsultationTraitementsType::class, $medicament);

        $consultation->setPatient($patient);
        $medicament->setConsultation($consultation);

        $form->handlRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $em->persist($medicament);
            $em->flush();
            return $medicament;
        }

        return $form;
    }

    /**
     * @Rest\View()
     * @Rest\Patch("/patients/{id}/consultations/{consultation}/medicaments/{medicament}")
     */
    public function patchMedicamentsAction(Request $request)
    {
        $em         = $this->getDoctrine()->getEntityManager();
        $medicament = $em->getMedicament($em, $request);
        $form      = $this->createForm(ConsultationTraitementsType::class, $medicament);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();
            return $affection;
        }

        return $form;

    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/patients/{id}/consultations/{consultation}/medicaments/{medicament}")
     */
    public function deleteMedicamentsAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $consultation = $this->getConsultation($em, $request);

        $medicament = $em->getRepository(ConsultationTraitements::class)->find($request->get('medicament'));
        $consultation->removeMedicament($medicament);
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
    private function getMedicament($em, Request $request)
    {
        return $em->getRepository(ConsultationTraitements::class)->find($request->get('medicament'));
    }
}
