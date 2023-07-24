<?php

namespace PS\ApiBundle\Controller;

use ApiBundle\File\ApiUploadedFile;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use PS\ApiBundle\Form\InfosPatientType;
use PS\ApiBundle\Form\AvatarType;
use PS\GestionBundle\Entity\Patient;
use PS\ParametreBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class PatientController extends Controller
{

    /**
     * @Rest\View(serializerGroups={"photo"})
     * @Rest\Post("/patients/{id}/photo")
     */
    public function postPhotoAction(Request $request)
    {
        $data = $request->request->all();

        //return $data;

        if ($request->request->has('file')) {

            $file = new ApiUploadedFile($request->request->get('file'));

            $image_name = $request->get('id') . '.jpeg';
            $path       = $file->getPathname();
            $size       = $file->getSize();

            $data['file'] = new UploadedFile($path, $image_name, 'image/jpeg', $size, null, true);

            $image = new Image();
            $form  = $this->createForm(new AvatarType(), $image);

            $form->submit($data);

            if ($form->isValid()) {

                $em = $this->getDoctrine()->getEntityManager();

                $image->setFile($data['file']);

                $em->persist($image);

                $em->flush();

                $patient  = $em->getRepository(Patient::class)->find($request->get('id'));
                $personne = $patient->getPersonne();

                $personne->setPhoto($image);

                $em->merge($personne);

                $em->flush();

                return $image;
            }

            return $form;

        }

    }

    /**
     * @Rest\View(serializerGroups={"patient", "user", "personne", "patient-assurance", "photo", "patient-allergie", "patient-vaccin", "patient-affection", "ville", "groupeSanguin", "rdv", "patient-contact", "patient-medecin", "specialite", "patient-attribut", "attribut", "pays"})
     * @Rest\Patch("/patients/{id}")
     */
    public function patchPatientAction(Request $request)
    {
        return $this->createOrUpdate($request);
    }

    /**
     * @Rest\View()
     * @Rest\Post("/patients/{id}")
     */
    public function postPatientAction(Request $request)
    {
        return $this->createOrUpdate($request);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function createOrUpdate(Request $request)
    {
        $em      = $this->getDoctrine()->getManager();
        $patient = $em->getRepository(Patient::class)->find($request->get('id'));

        if (empty($patient)) {
            return $this->patientNotFound();
        }

        $data = $request->request->all();
        $contact = $data['contact'];

        $data = array_except($data, ['lignes_attributs', 'contact']);

        //echo json_encode($data);exit;

        $form = $this->createForm(InfosPatientType::class, $patient);
        $form->submit($data, $request->getMethod() != 'PATCH');

        if ($form->isValid()) {

            $patient->getPersonne()->setContact($contact);

            $em->merge($patient);
            $em->flush();
            return $patient;
        }

        return $form;
    }

    /**
     * @Rest\View(serializerGroups={"patient", "user", "personne", "patient-assurance", "photo", "patient-allergie", "patient-vaccin", "patient-affection", "ville", "groupeSanguin", "rdv", "patient-contact", "patient-medecin", "specialite", "patient-attribut", "attribut", "pays"})
     * @Rest\Get("/patients/{id}")
     */
    public function getPatientAction(Request $request)
    {
        $em      = $this->getDoctrine()->getManager();
        $patient = $em->getRepository(Patient::class)->find($request->get('id'));
        

        if (!$patient) {
            
            return $this->patientNotFound();
        }

        return $patient;
    }

    /**
     * Message d'erreur, patient introuvable
     * @return string
     */
    public function patientNotFound()
    {
        throw $this->createNotFoundException('Patient inexistant');
    }

}
