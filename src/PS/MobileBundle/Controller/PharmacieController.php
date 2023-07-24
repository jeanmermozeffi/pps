<?php

namespace PS\MobileBundle\Controller;


use PS\GestionBundle\Entity\Pharmacie;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use PS\ParametreBundle\Entity\Ville;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;


class PharmacieController extends Controller
{
    use ApiTrait;

    /**
     * @Rest\View(serializerGroups={"pharmacie", "info-pharmacie"})
     * @QueryParam(name="page", requirements="\d+", default=1, description="Index de début de la pagination")
     * @QueryParam(name="limit", requirements="\d+", default=20, description="Nombre d'éléments")
     * @Rest\Get("/pharmacies")
     */
    public function getPharmaciesAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $page     = intval($paramFetcher->get('page'));
        $limit    = intval($paramFetcher->get('limit'));
        $rep      = $this->getRepository(Pharmacie::class);
        $total = $rep->createQueryBuilder('a')
            // Filter by some parameter if you want
            // ->where('a.published = 1')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $maxPages = ceil($total / $limit);

        if ($page <= 0 || $page > $maxPages) {
            $page = 1;
        }

        $offset = ($page - 1) * $limit;

        return [
            'maxPages' => $maxPages
            ,  'data' => $rep
                ->findBy([], ['libPharmacie' => 'ASC'], $limit, $offset)
        ];

    }

}
