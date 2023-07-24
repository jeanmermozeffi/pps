<?php

namespace PS\MobileBundle\Controller;


use PS\ParametreBundle\Entity\Nationalite;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class NationaliteController extends Controller
{
    use ApiTrait;

    /**
     * @Rest\View(serializerGroups={"nationalite"})
     * @Rest\Get("/nationalites")
     */
    public function getNationalitesAction(Request $request)
    {
        return $this->getRepository(Nationalite::class)
                ->findAll();
    }

}
