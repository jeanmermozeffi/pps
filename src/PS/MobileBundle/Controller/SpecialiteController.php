<?php

namespace PS\MobileBundle\Controller;


use PS\GestionBundle\Entity\Medecin;
use PS\ParametreBundle\Entity\Ville;
use PS\ParametreBundle\Entity\Specialite;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class SpecialiteController extends Controller
{
    use ApiTrait;

    /**
     * @Rest\View(serializerGroups={"specialite"})
     * @Rest\Get("/specialites")
     */
    public function getSpecialitesAction(Request $request)
    {
        return $this->getRepository(Specialite::class)
                ->findAll();
    }


    /**
     * @Rest\View(serializerGroups={"medecin", "personne", "hopital", "info-hopital", "specialite"})
     * @Rest\Get("/specialites/{id}/medecins")
     */
    public function getMedecinsAction(Request $request)
    {
        return $this->getRepository(Medecin::class)
            ->findBySpecialite($request->get('id'));
    }

}
