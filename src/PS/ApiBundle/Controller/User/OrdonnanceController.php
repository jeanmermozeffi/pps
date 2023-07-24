<?php

namespace PS\ApiBundle\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PS\GestionBundle\Entity\Consultation;
use PS\GestionBundle\Entity\ConsultationTraitements;
use PS\ParametreBundle\Entity\Affection;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle

class OrdonnanceController extends Controller
{
     /**
     * @Rest\View()
     * @Rest\Get("users/{id}/ordonnances")
     */
    public function getAllOrdonnanceAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $ordonnances = $em->getRepository(Consultation::class)->findOrdonnanceByPatient($request->get('id'));
        if ($ordonnances) {
            return $ordonnances;
        }

        throw $this->createNotFoundException('Aucune ordonnance associée à ce patient');

    }


    /**
     * @Rest\View()
     * @Rest\Get("users/{id}/ordonnances/{consultation}")
     */
    public function getOrdonnanceAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $medicaments = $em->getRepository(ConsultationTraitements::class)
            ->findByPatientConsultation($request->get('id'), $request->get('consultation'));

        if ($medicaments) {
            return $medicaments;
        }

        throw $this->createNotFoundException('Aucun médicament associé à cette ordonnances');

    }
}
