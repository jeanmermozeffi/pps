<?php

namespace PS\MobileBundle\Controller\Patient;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PS\MobileBundle\Controller\ApiTrait;
use PS\ParametreBundle\Entity\LigneAssurance;
use PS\ParametreBundle\Form\LigneAssuranceType;

class AssuranceController extends Controller
{
    use ApiTrait;

    /**
     * @Rest\View(serializerGroups={"patient-assurance", "assurance"})
     * @Rest\Get("/patients/{id}/assurances")
     */
    public function getAssurancesAction(Request $request)
    {
        $patient = $this->getPatient($request->get('id'));
        return $patient->getLigneAssurances();
    }


    /**
     * Retourne l'Assurance du patient
     *
     * @param integer $patient
     * @param integer $id
     * @return LigneAssurance|null
     */
    private function getAssurance(int $patient, int $id): ?LigneAssurance
    {
        return $this->getRepository(LigneAssurance::class)->findOneBy(compact('patient', 'id'));
    }

    /**
     * @Rest\View(serializerGroups={"patient-assurance", "assurance"})
     * @Rest\Get("/patients/{id}/assurances/{assurance}")
     */
    public function getAssuranceAction(Request $request)
    {
        $ligneAssurance = $this->getAssurance($request->get('id'), $request->get('assurance'));

        if ($ligneAssurance) {
            return $ligneAssurance;
        }

        return $this->notFound('Assurance avec ID ' . $request->get('assurance') . ' inexistant ou n\'est pas associé à votre compte');
    }


    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/patients/{id}/assurances/{assurance}")
     */
    public function deleteAssuranceAction(Request $request)
    {
        $ligneAssurance = $this->getAssurance($request->get('id'), $request->get('assurance'));

        $patient = $this->getPatient($request->get('id'));

        if ($ligneAssurance) {
            $patient->removeLigneAssurance($ligneAssurance);
            $this->getManager()->flush();
            return new Response();
        }

        return $this->notFound('Assurance avec ID ' . $request->get('assurance') . ' inexistante ou n\'est pas associé à votre compte');
    }


    /**
     * @Rest\View(serializerGroups={"patient-assurance", "assurance"}, statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/patients/{id}/assurances")
     */
    public function postAssuranceAction(Request $request)
    {
        $patient = $this->getPatient($request->get('id'));

        $data = $this->formatValue($request->request->all());

        $ligneAssurance = new LigneAssurance();
        $ligneAssurance->setPatient($patient);

        $form = $this->createForm(LigneAssuranceType::class, $ligneAssurance, [
            'csrf_protection' => false
        ]);


        $form->submit($data);

        if ($form->isValid()) {
            $em = $this->getManager();
            $em->persist($ligneAssurance);
            $em->flush();
            return $ligneAssurance;
        }

        return $form;
    }



    /**
     * @Rest\View(serializerGroups={"patient-assurance"})
     * @Rest\Put("/patients/{id}/assurances/{assurance}")
     */
    public function putAssuranceAction(Request $request)
    {
        $ligneAssurance = $this->getAssurance($request->get('id'), $request->get('assurance'));
        $data = $this->formatValue($request->request->all());

        $form = $this->createForm(LigneAssuranceType::class, $ligneAssurance, [
            'csrf_protection' => false
        ]);


        $form->submit($data);

        if ($form->isValid()) {
            $em = $this->getManager();
            $em->flush();
            return $ligneAssurance;
        }

        return $form;
    }
}
