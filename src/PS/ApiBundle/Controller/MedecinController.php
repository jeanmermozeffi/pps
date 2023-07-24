<?php

namespace PS\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PS\GestionBundle\Entity\Medecin;
use PS\ApiBundle\Form\InfosMedecinType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle

class MedecinController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"user", "personne", "photo", "rdv"})
     * @Rest\Get("/medecins/{id}")
     */
    public function getMedecinsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $medecin = $em->getRepository(Medecin::class)->find($request->get('id'));
        if (empty($medecin)) {
            throw $this->createNotFoundException('Medecin avec cet ID inexistant');
        }

        return $medecin;
    }

    /**
     * @Rest\View()
     * @Rest\Patch("/medecins/{id}")
     */
    public function patchMedecinsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $medecin = $em->getRepository(Medecin::class)->find($request->get('id'));
        
        if (empty($medecin)) {
            throw $this->createNotFoundException();
        }
        $form = $this->createForm(InfosMedecinType::class, $medecin);
        $form->submit($request->request->all(), $request->getMethod() != 'PATCH');

        if ($form->isValid()) {

            $em->merge($medecin);
            $em->flush();
            return $medecin;
        }

        return $form;
    }
}
