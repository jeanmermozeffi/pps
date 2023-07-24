<?php

namespace PS\MobileBundle\Controller\Patient;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Entity\PatientQuestionnaire;
use PS\GestionBundle\Entity\DonneeQuestionnaire;
use PS\SiteBundle\Form\PatientRechercherForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use PS\MobileBundle\Controller\ApiTrait;

class SoumissionController extends Controller
{
    use ApiTrait;

    /**
     * @Rest\View(serializerGroups={"questionnaire", "soumission-questionnaire"})
     * @Rest\Get("/patients/{id}/questionnaires/{questionnaire}/soumissions")
     */
    public function getSoumissionsAction(Request $request)
    {
        $questionnaire = $this->getQuestionnaire($request->get('questionnaire'));
        $patient = $this->getPatient($request->get('id'));
        $soumissions = $patient->getQuestionnaires();
        return $soumissions->filter(function (PatientQuestionnaire $soumission) use($questionnaire) {
            return $soumission->getQuestionnaire() == $questionnaire;
        });
    }


    /**
     * @Rest\View(serializerGroups={"soumission-questionnaire", "donnee-soumission", "traitement-soumission", "medecin", "hopital", "info-hopital", "diagnostic-soumission", "personne", "specialite"})
     * @Rest\Get("/patients/{patient}/questionnaires/{questionnaire}/soumissions/{id}")
     */
    public function getSoumissionAction(Request $request)
    {
        
        return $this->getRepository(PatientQuestionnaire::class)->findOneBy([
            'patient' => $request->get('patient')
            , 'id' => $request->get('id')
            , 'questionnaire' => $request->get('questionnaire')
        ]);
    }


}
