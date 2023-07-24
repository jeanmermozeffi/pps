<?php

namespace PS\MobileBundle\Controller;

use FOS\RestBundle\View\View;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use PS\ParametreBundle\Entity\Ville;
use PS\GestionBundle\Entity\Questionnaire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use PS\GestionBundle\Entity\QuestionnaireDepistage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class VilleController extends Controller
{
    use ApiTrait;

    /**
     * @Rest\View(serializerGroups={"ville"})
     * @Rest\Get("/villes")
     */
    public function getVillesAction(Request $request)
    {
        return $this->getRepository(Ville::class)
                ->findAll();
    }

}
