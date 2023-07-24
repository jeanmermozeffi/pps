<?php

namespace PS\GestionBundle\Repository;
use PS\GestionBundle\Entity\Fiche;
/**
 * FicheRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FicheRepository extends \Doctrine\ORM\EntityRepository
{
    public function reference()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('MAX(l.reference) AS max_ordre')
            ->from(Fiche::class, 'l')
            ->setMaxResults(1);

        return intval($qb->getQuery()->getSingleScalarResult()) + 1;
    }


    public function cpn($patient)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('MAX(l.cpn AS cpn')
            ->from(Fiche::class, 'l')
            ->andWhere('l.patient = :patient')
            ->setParameter('patient', $patient)
            ->setMaxResults(1);

        return intval($qb->getQuery()->getSingleScalarResult()) + 1;
    }
}
