<?php

namespace PS\MobileBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use PS\GestionBundle\Entity\QuestionnaireDepistage;
use PS\GestionBundle\Entity\Questionnaire;
use Symfony\Component\HttpFoundation\Response;


class QuestionnaireController extends Controller
{
    use ApiTrait;

    /**
     * @Rest\View(serializerGroups={"questionnaire"})
     * @Rest\Get("/questionnaires")
     */
    public function getQuestionnairesAction(Request $request)
    {
        return $this->getRepository(Questionnaire::class)
                ->findAll();
    }



     /**
     * @Rest\View(serializerGroups={"ligne-questionnaire", "valeur-ligne-questionnaire", "questionnaire"})
     * @Rest\Get("/questionnaires/{id}/lignes")
     */
    public function getLigneQuestionnairesAction(Request $request)
    {
        $questionnaire = $this->getRepository(Questionnaire::class)->find($request->get('id'));

        if (!$questionnaire) {
           return $this->notFound('Questionnaire inexistant de notre BDD');
        }

        //$lignes = $questionnaire->getLignes();

        return $questionnaire;
    }
}
