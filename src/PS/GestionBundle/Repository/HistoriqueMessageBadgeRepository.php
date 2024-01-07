<?php

namespace PS\GestionBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PS\GestionBundle\Entity\HistoriqueMessageBadge;
use PS\GestionBundle\Entity\Patient;

/**
 *
 */
class HistoriqueMessageBadgeRepository extends EntityRepository
{

   /**
    * @return HistoriqueMessageBadge[] Returns an array of HistoriqueMessageBadge objects
    */
   public function findByPatientField(Patient $patient): array
   {
       return $this->createQueryBuilder('h')
           ->andWhere('h.patient = :patient')
           ->setParameter('patient', $patient)
           ->orderBy('h.id', 'ASC')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?HistoriqueMessageBadge
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
