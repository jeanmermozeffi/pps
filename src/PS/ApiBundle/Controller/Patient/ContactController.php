<?php

namespace PS\ApiBundle\Controller\Patient;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use PS\ApiBundle\Form\TelephoneType;
use PS\GestionBundle\Entity\Patient;
use PS\ParametreBundle\Entity\Telephone;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Utilisation de la vue de FOSRestBundle

class ContactController extends Controller
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
     * @Rest\View(serializerGroups={"patient-contact"})
     * @Rest\Get("patients/{id}/contacts")
     */
    public function getAllAction(Request $request)
    {
        $em       = $this->getDoctrine()->getEntityManager();
        $contacts = $em->getRepository(Telephone::class)->findByPatient($request->get('id'));
        if ($contacts) {
            return $contacts;
        }

        throw $this->createNotFoundException('Aucun contact n\'est associé à ce patient');
    }

    /**
     * @Rest\View(serializerGroups={"patient-contact"})
     * @Rest\Get("patients/{id}/contacts/{contact}")
     */
    public function getContactAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);

        if ($patient) {
            $contact = $this->getContact($patient, $request);

            if ($contact) {
                return $contact;
            }

            throw $this->createAccessDeniedException('Vous n\'avez pas les permissions pour accéder à cette ressource');
        }

        throw $this->createNotFoundException('Patient introuvale');
    }

    /**
     * @param $patient
     * @param Request $request
     * @return mixed
     */
    private function getContact(Patient $patient, Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        return $em->getRepository(Telephone::class)
            ->findOneBy([
                'id' => $request->get('contact')
                , 'patient' => $patient->getId(),
            ]);
    }

    /**
     * @Rest\View(serializerGroups={"patient-contact"})
     * @Rest\Put("patients/{id}/contacts/{contact}")
     */
    public function putContactAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);

        if ($patient) {
            $contact = $this->getContact($patient, $request);

            if ($contact) {
                $form = $this->createForm(TelephoneType::class, $contact);
                $form->submit($request->request->all());

                if ($form->isValid()) {
                    $em->merge($contact);
                    $em->flush();
                    return $contact;
                }

                return $form;
            }

            throw $this->createAccessDeniedException('Vous n\'avez pas les permissions pour accéder à cette ressource');

        }

        throw $this->createNotFoundException('Patient inexistant');
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"patient-contact"})
     * @Rest\Post("patients/{id}/contacts")
     */
    public function postContactAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);

        if ($patient) {
            $contact = new Telephone();
            $form    = $this->createForm(TelephoneType::class, $contact);

            $contact->setPatient($patient);

            $form->submit($request->request->all());

            if ($form->isValid()) {
                $em->persist($contact);
                $em->flush();
                return $contact;
            }

            return $form;
        }

        throw $this->createNotFoundException('Patient introuvale');

    }

    /**
     * @Rest\View()
     * @Rest\Delete("patients/{id}/contacts/{contact}")
     */
    public function deleteContactAction(Request $request)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);

        if ($patient) {
            $contact = $this->getContact($patient, $request);
            if (!$contact) {
                throw $this->createAccessDeniedException('Vous n\'avez pas les permissions pour accéder à cette ressource');
            }

            $patient->removeTelephone($contact);
            $em->flush();
        } else {
             throw $this->createNotFoundException('Patient inexistant');
        }

       

    }
}
