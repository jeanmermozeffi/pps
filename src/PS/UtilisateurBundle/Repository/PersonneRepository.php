<?php

namespace PS\UtilisateurBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PersonneRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PersonneRepository extends EntityRepository
{
    public function exists($nom, $prenom)
    {
        $em = $this->getEntityManager();
        $qb = $em
            ->createQuery('SELECT COUNT(a) FROM UtilisateurBundle:Personne a WHERE LOWER(a.nom) = ?1 AND LOWER(a.prenom) = ?2')
            ->setParameter(1, mb_strtolower($nom))
            ->setParameter(2, mb_strtolower($prenom));

         return $qb->getSingleScalarResult();
    }



    
    public function getCustomerNumbers()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select("REPLACE(REPLACE(a.contact, ' ', ''), '/', '') AS contact")
            ->distinct()
            ->from('UtilisateurBundle:Personne', 'a')
            ->leftJoin('UtilisateurBundle:Utilisateur', 'u', 'WITH', '(a.id = u.personne) AND (u.roles LIKE \'%"ROLE_CUSTOMER"%\')')
            ->andWhere('REGEXP(a.contact, \'^\+?[0-9]+$\')  = true')
            ->andWhere(
                $qb->expr()->orX(
                  $qb->expr()->isNotNull('a.contact'),
                  $qb->expr()->neq('a.contact', "'0000000'")
              ))
            ->getQuery()
            ->getResult();
    }



    public function getRelativeNumbers()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select("REPLACE(REPLACE(a.numero, ' ', ''), '/', '') AS contact")
            ->distinct()
            ->from('ParametreBundle:Telephone', 'a')
            ->orWhere("TRIM(a.numero) <> ''")
            ->orWhere('a.numero IS NOT NULL')
            ->orWhere("a.numero <> '0000000'")
            ->getQuery()
            ->getResult();
    }



    public function getCustomerByCorporate($corporate, $dates = [])
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select("p2.lieuhabitation, p.nom, p.prenom, REPLACE(REPLACE(p.contact, ' ', ''), '/', ',') AS contact, p2.identifiant, p2.pin")
            
            ->from('UtilisateurBundle:Personne', 'p')
            ->join('GestionBundle:Patient', 'p2', 'WITH', 'p.id = p2.personne')
            ->andWhere("p.corporate = :corporate")
            /*->andWhere('p.contact IS NOT NULL')
            ->andWhere("p.contact <> '0000000'")*/
            ->setParameter('corporate', $corporate);
        
            if (!empty($dates['debut'])) {
                $qb->andWhere('DATE(p.dateInscription) >= :debut');
                $qb->setParameter('debut', $dates['debut']);
            }

            if (!empty($dates['fin'])) {
                $qb->andWhere('DATE(p.dateInscription)  <= :fin');
                $qb->setParameter('fin', $dates['fin']);
            }
        
        return $qb->getQuery()
            ->getResult();
       
    }



    public function getParentByCorporate($corporate, $dates = [])
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select("CONCAT(a.nom, ' (', a.lien, ')') AS nom_parent, p.nom, p.prenom, REPLACE(REPLACE(a.numero, ' ', ''), '/', '') AS contact")
            
            ->from('ParametreBundle:Telephone', 'a')
            ->join('GestionBundle:Patient', 'p2', 'WITH', 'p2.id = a.patient')
            ->join('UtilisateurBundle:Personne', 'p', 'WITH', 'p.id = p2.personne')
            ->andWhere("p.corporate = :corporate")
            ->andWhere(
                $qb->expr()->orX(
                  $qb->expr()->isNotNull('a.numero'),
                  $qb->expr()->neq('a.numero', "'0000000'"),
                  $qb->expr()->neq('a.numero', "''")
              ));

            if (!empty($dates['debut'])) {
                $qb->andWhere('DATE(p.dateInscription) >= :debut');
                $qb->setParameter('debut', $dates['debut']);
            }

            if (!empty($dates['fin'])) {
                $qb->andWhere('DATE(p.dateInscription)  <= :fin');
                $qb->setParameter('fin', $dates['fin']);
            }

            $qb->setParameter('corporate', $corporate);




        return $qb->getQuery()
            ->getResult();
       
    }
}