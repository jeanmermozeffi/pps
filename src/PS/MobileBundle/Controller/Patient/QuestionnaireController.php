<?php

namespace PS\MobileBundle\Controller\Patient;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use PS\GestionBundle\Entity\Patient;
use PS\SiteBundle\Form\PatientRechercherForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use PS\MobileBundle\Controller\ApiTrait;
use PS\GestionBundle\Entity\PatientQuestionnaire;
use PS\GestionBundle\Entity\DonneeQuestionnaire;
use PS\GestionBundle\Entity\LigneQuestionnaire;
use Doctrine\Common\Collections\ArrayCollection;


class QuestionnaireController extends Controller
{
    use ApiTrait;




    /**
     * @Rest\View(serializerGroups={"ligne-questionnaire", "valeur-ligne-questionnaire", "questionnaire"}, statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/patients/{id}/questionnaires/{questionnaire}")
     */
    public function postQuestionnaireAction(Request $request)
    {
        $questionnaire = $this->getQuestionnaire($request->get('questionnaire'));
        $patient = $this->getPatient($request->get('id'));
        $soumission = new PatientQuestionnaire();
        $soumission->setDate(new \DateTime());
        $soumission->setPatient($patient);
        $soumission->setQuestionnaire($questionnaire);
        $data = $request->request->all();

        /**
         * @var \Symfony\Component\Form\Form $form
         */
        $form = $this->get('app.form_questionnaire')->generateForm($questionnaire, ['csrf_protection' => false]);
        $form->submit($data);

        if ($form->isValid()) {
        }

        return $form;
    }
}
