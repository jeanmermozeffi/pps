<?php

namespace PS\ParametreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MedecinRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PatientAffectionsRepository extends EntityRepository
{
    public function findByPatient($id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('a.id, a.affection, a.commentaire')
            ->from('ParametreBundle:PatientAffections', 'a')
            ->where("a.patient = :id")
            ->orderBy('a.id',   'DESC')
            ->setParameter('id', $id);

        return $qb->getQuery()->getResult();

    }


    public function findOneByPatient($id, $allergie)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('a.id, a.affection, a.commentaire')
            ->from('ParametreBundle:PatientAffections', 'a')
            ->where("a.patient = :id")
            ->andWhere('a.id = :allergie')
            ->setParameter('id', $id)
            ->setParameter('allergie', $allergie);

        return $qb->getQuery()->getOneOrNullResult();

    }

}