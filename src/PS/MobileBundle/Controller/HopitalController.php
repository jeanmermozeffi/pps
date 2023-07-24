<?php

namespace PS\MobileBundle\Controller;


use PS\ParametreBundle\Entity\Hopital;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use PS\ParametreBundle\Entity\Ville;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;

class HopitalController extends Controller
{
    use ApiTrait;

    /**
     * @Rest\View(serializerGroups={"hopital", "info-hopital", "specialite-hopital", "specialite", "pays"})
     * @QueryParam(name="page", requirements="\d+", default=1, description="Index de début de la pagination")
     * @QueryParam(name="limit", requirements="\d+", default=20, description="Nombre d'éléments")
     * @Rest\Get("/hopitaux")
     */
    public function getHopitauxAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
       $page     = intval($paramFetcher->get('page'));
        $limit    = intval($paramFetcher->get('limit'));
        $rep      = $this->getRepository(Hopital::class);
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
                ->findBy([], ['nom' => 'ASC'], $limit, $offset)
        ];
    }

}
