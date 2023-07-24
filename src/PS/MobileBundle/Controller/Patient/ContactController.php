<?php

namespace PS\MobileBundle\Controller\Patient;

use FOS\RestBundle\Controller\Annotations as Rest;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PS\MobileBundle\Controller\ApiTrait;
use PS\ParametreBundle\Entity\Telephone;
use PS\ParametreBundle\Form\TelephoneType;

class ContactController extends Controller
{
    use ApiTrait;

    /**
     * @Rest\View(serializerGroups={"patient-contact"})
     * @Rest\Get("/patients/{id}/contacts")
     */
    public function getContactsAction(Request $request)
    {
        $patient = $this->getPatient($request->get('id'));
        return $patient->getTelephones();
    }


    /**
     * Retourne l'contact du patient
     *
     * @param integer $patient
     * @param integer $id
     * @return Telephone|null
     */
    private function getContact(int $patient, int $id): ?Telephone
    {
        return $this->getRepository(Telephone::class)->findOneBy(compact('patient', 'id'));
    }

    /**
     * @Rest\View(serializerGroups={"patient-contact"})
     * @Rest\Get("/patients/{id}/contacts/{contact}")
     */
    public function getContactAction(Request $request)
    {
        $contact = $this->getContact($request->get('id'), $request->get('contact'));

        if ($contact) {
            return $contact;
        }

        return $this->notFound('Contact avec ID ' . $request->get('contact') . ' inexistant ou n\'est pas associé à votre compte');
    }


    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/patients/{id}/contacts/{contact}")
     */
    public function deleteContactAction(Request $request)
    {
        $contact = $this->getContact($request->get('id'), $request->get('contact'));

        $patient = $this->getPatient($request->get('id'));

        if ($contact) {
            $patient->removeTelephone($contact);
            $this->getManager()->flush();
            return new Response();
        }

        return $this->notFound('Contact avec ID ' . $request->get('contact') . ' inexistant ou n\'est pas associé à votre compte');
    }


    /**
     * @Rest\View(serializerGroups={"patient-contact"}, statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/patients/{id}/contacts")
     */
    public function postContactAction(Request $request)
    {
        $patient = $this->getPatient($request->get('id'));

        $contact = new Telephone();
        $contact->setPatient($patient);

        $form = $this->createForm(TelephoneType::class, $contact, [
            'csrf_protection' => false
        ]);

        
        $form->submit($this->formatValue($request->request->all()));

        if ($form->isValid()) {
            $em = $this->getManager();
            $em->persist($contact);
            $em->flush();
            return $contact;
        }

        return $form;
    }



    /**
     * @Rest\View(serializerGroups={"patient-contact"})
     * @Rest\Put("/patients/{id}/contacts/{contact}")
     */
    public function putContactAction(Request $request)
    {
      
        $contact = $this->getContact($request->get('id'), $request->get('contact'));


        $form = $this->createForm(TelephoneType::class, $contact, [
            'csrf_protection' => false
        ]);


        $form->submit($this->formatValue($request->request->all()));

        if ($form->isValid()) {
            $em = $this->getManager();
            $em->flush();
            return $contact;
        }

        return $form;
    }
}
