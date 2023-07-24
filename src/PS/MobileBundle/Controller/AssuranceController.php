<?php

namespace PS\MobileBundle\Controller;


use PS\ParametreBundle\Entity\Assurance;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class AssuranceController extends Controller
{
    use ApiTrait;

    /**
     * @Rest\View(serializerGroups={"assurance"})
     * @Rest\Get("/assurances")
     */
    public function getAssurancesAction(Request $request)
    {
        return $this->getRepository(Assurance::class)
                ->findAll();
    }

}
