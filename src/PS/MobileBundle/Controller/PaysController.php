<?php

namespace PS\MobileBundle\Controller;


use PS\ParametreBundle\Entity\Pays;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use PS\ParametreBundle\Entity\Ville;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class PaysController extends Controller
{
    use ApiTrait;

    /**
     * @Rest\View(serializerGroups={"pays"})
     * @Rest\Get("/pays")
     */
    public function getPaysAction(Request $request)
    {
        return $this->getRepository(Pays::class)
                ->findAll();
    }


    /**
     * @Rest\View(serializerGroups={"ville"})
     * @Rest\Get("/pays/{id}/villes")
     */
    public function getVillesAction(Request $request)
    {
        return $this->getRepository(Ville::class)
            ->findByPays($request->get('id'));
    }

}
