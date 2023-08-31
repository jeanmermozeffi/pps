<?php

namespace PS\GestionBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MedecinRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MedecinRepository extends EntityRepository
{
    public function findPersoByParam($id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('m')
            ->from('GestionBundle:Medecin', 'm')
            ->where("m.personne = :id")
            ->setParameter('id', $id);

        return $qb->getQuery()->getResult();

    }
}