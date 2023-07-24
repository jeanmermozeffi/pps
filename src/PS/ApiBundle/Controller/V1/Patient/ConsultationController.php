<?php

namespace PS\ApiBundle\Controller\V1\Patient;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\Version;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use PS\GestionBundle\Entity\Consultation;
use PS\GestionBundle\Entity\Medecin;
use PS\GestionBundle\Entity\Patient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Version("v1")
 */
class ConsultationController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"consultation", "specialite"})
     * @QueryParam(name="page", requirements="\d+", default="", description="Index de début de la pagination")
     * @Rest\Get("patients/{id}/consultations", condition="request.attributes.get('version') == 'v1'")
     */
    public function getConsultationsAction(Request $request, ParamFetcher $paramFetcher)
    {
        $em       = $this->getDoctrine()->getEntityManager();
        $id       = $request->get('id');
        $rep      = $em->getRepository(Consultation::class);
        $page     = intval($paramFetcher->get('page'));
        $result   = 10;
        $maxPages = ceil($rep->countByPatient($id) / $result);

        if ($page <= 0 || $page > $maxPages) {
            $page = 1;
        }

        $limit  = $result;
        $offset = ($page - 1) * $result;

        $consultations = $rep
            ->findByPatient(
                $id
                , $limit
                , $offset
            );

        return ['maxPages' => $maxPages, 'results' => $consultations];

    }

    /**
     * @Rest\View()
     * @Rest\Get("patients/{id}/consultations/{consultation}")
     */
    public function getConsultationAction(Request $request)
    {
        $em           = $this->getDoctrine()->getEntityManager();
        $consultation = $em->getRepository(Consultation::class)->find($request->get('consultation'));
        if ($consultation) {
            return $consultation;
        }

        return $this->createNotFoundException('Aucune consultation n\'est associé à cet ID');
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("patients/{id}/consultations")
     */
    public function postConsultationAction(Request $request)
    {
        $em           = $this->getDoctrine()->getEntityManager();
        $consultation = new Consultation();
        $form         = $this->createForm(ConsultationType::class, $consultation);
        $medecin      = $em->getRepository(Medecin::class)->findPersoByParam($this->getUser()->getPersonne()->getId());
        $patient      = $em->getRepository(Patient::class)->find($request->get('id'));

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
        $em           = $this->getDoctrine()->getEntityManager();
        $consultation = $em->getRepository(Consultation::class)->get($request->get('consultation'));
        $form         = $this->createForm(ConsultationType::class, $consultation);

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
