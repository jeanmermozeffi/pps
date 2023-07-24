<?php

namespace PS\ApiBundle\Controller\Medecin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PS\GestionBundle\Entity\Consultation;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View;

class ConsultationController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"consultation"})
     * @Rest\Get("medecins/{id}/consultations")
     */
    public function getConsultationsAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $consultations = $em->getRepository(Consultation::class)->findByMedecin($request->get('id'));
        if ($consultations) {
            return $consultations;
        }

        return $this->createNotFoundException('Aucune consultation n\'est associé à ce patient');
    }
}
