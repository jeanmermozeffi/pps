<?php

namespace PS\MobileBundle\Controller;


use PS\ParametreBundle\Entity\GroupeSanguin;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class GroupeSanguinController extends Controller
{
    use ApiTrait;

    /**
     * @Rest\View(serializerGroups={"groupe-sanguin"})
     * @Rest\Get("/groupe-sanguins")
     */
    public function getGroupeSanguinsAction(Request $request)
    {
        return $this->getRepository(GroupeSanguin::class)
                ->findAll();
    }

}
