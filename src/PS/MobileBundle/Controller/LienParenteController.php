<?php

namespace PS\MobileBundle\Controller;


use PS\ParametreBundle\Entity\LienParente;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use PS\ParametreBundle\Entity\Ville;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class LienParenteController extends Controller
{
    use ApiTrait;

    /**
     * @Rest\View(serializerGroups={"lien-parente"})
     * @Rest\Get("/lien-parente")
     */
    public function getLienParentesAction(Request $request)
    {
        return $this->getRepository(LienParente::class)
                ->findAll();
    }

}
