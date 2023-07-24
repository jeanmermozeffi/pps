<?php

namespace PS\ApiBundle\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PS\ParametreBundle\Entity\Telephone;
use PS\ApiBundle\Form\TelephoneType;
use PS\GestionBundle\Entity\Patient;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle

class ContactController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"patient-contact"})
     * @Rest\Get("users/{id}/contacts")
     */
    public function getAllAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $contacts = $em->getRepository(Telephone::class)->findByPatient($request->get('id'));
        if ($contacts) {
            return $contacts;
        }

        throw $this->createNotFoundException('Aucun contact n\'est associé à ce patient');
    }


    /**
     * @Rest\View(serializerGroups={"patient-contact"})
     * @Rest\Get("users/{id}/contacts/{contact}")
     */
    public function getContactAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $contact = $em->getRepository(Telephone::class)->findOneByPatient($request->get('id'), $request->get('contact'));
        
        if ($contact) {
            return $contact;
        }
        
        throw $this->createNotFoundException('Contact introuvale');
    }

    /**
     * @Rest\View()
     * @Rest\Patch("users/{id}/contacts/{contact}")
     */
    public function patchContactAction(Request $request)
    {
         $em = $this->getDoctrine()->getEntityManager();
         $contact = $em->getRepository(Telephone::class)->find($request->get('contact'));
         $form = $this->createForm(TelephoneType::class, $contact);
         $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();
            return $contact;
        }

        return $form;
    }


    /**
     * @Rest\View(serializerGroups={"patient-contact"})
     * @Rest\Put("users/{id}/contacts/{contact}")
     */
    public function putContactAction(Request $request)
    {
         $em = $this->getDoctrine()->getEntityManager();
         $contact = $em->getRepository(Telephone::class)->find($request->get('contact'));
         $form = $this->createForm(TelephoneType::class, $contact);
         $form->submit($request->request->all());

        if ($form->isValid()) {
            $em->persist($contact);
            $em->flush();
            return $contact;
        }

        return $form;
    }


    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"patient-contact"})
     * @Rest\Post("users/{id}/contacts")
     */
    public function postContactAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $patient = $em->getRepository(Patient::class)->find($request->get('id'));

        $contact = new Telephone();
        $form = $this->createForm(TelephoneType::class, $contact);

        $contact->setPatient($patient);

        $form->submit($request->request->all());

        

        if ($form->isValid()) {
            $em->persist($contact);
            $em->flush();
            return $contact;
        }

        return $form;
    }


    /**
     * @Rest\View()
     * @Rest\Delete("users/{id}/contacts/{contact}")
     */
    public function deleteContactAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $patient = $em->getRepository(Patient::class)->find($request->get('id'));
         
        $contact = $em->getRepository(Telephone::class)->find($request->get('contact'));
        $patient->removeTelephone($contact);
        $em->flush();
    }
}
