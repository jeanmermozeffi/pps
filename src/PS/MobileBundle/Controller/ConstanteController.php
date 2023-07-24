<?php

namespace PS\MobileBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use PS\ParametreBundle\Entity\Constante;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ConstanteController extends Controller
{
    use ApiTrait;

    /**
     * @Rest\View(serializerGroups={"constante"})
     * @Rest\Get("/constantes")
     */
    public function getConstantesAction(Request $request)
    {
        return $this->getRepository(Constante::class)
                ->findAll();
    }

}
