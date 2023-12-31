<?php

namespace PS\ParametreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AnalyseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HopitalRepository extends EntityRepository
{
    public function findByCorporates($corporate)
    {
        $hopitaux = $corporate->getHopitauxId();
        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('a')
                ->from('PS\\ParametreBundle\\Entity\\Hopital', 'a')
                ->where('a.id IN (:hopitaux)')
                ->setParameter('hopitaux', $hopitaux);
        
    }


      /**
     * @param $result
     * @return mixed
     */
    public function points($result = true)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('a.nom,  b.nomResponsable, b.localisationHopital, b.contacts')
            ->from('ParametreBundle:Hopital', 'a')
            ->join('a.info', 'b')
            
            ->andWhere('b.pointVente = 1');
        

        return $result ? $qb->getQuery()->getResult() : $qb;

    }
}
