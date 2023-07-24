<?php

namespace PS\MobileBundle\Controller;


use PS\ParametreBundle\Entity\Statut;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use PS\ParametreBundle\Entity\Ville;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class StatutController extends Controller
{
    use ApiTrait;

    /**
     * @Rest\View(serializerGroups={"statut"})
     * @Rest\Get("/statuts")
     */
    public function getStatutsAction(Request $request)
    {
        return $this->getRepository(Statut::class)
                ->findAll();
    }

}
