<?php

namespace PS\GestionBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use  Doctrine\ORM\NoResultException;

/**
 * MedecinRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PatientRepository extends EntityRepository
{
    public function findByPass($id, $pin)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('count(p)')
            ->from('ParametreBundle:Pass', 'p')
            ->where("p.identifiant = :id")
            ->andWhere('p.pin = :pin')
            ->andWhere('p.actif = 1')
            ->setParameter('id', $id)
            ->setParameter('pin', $pin);

        return $qb->getQuery()->getResult();
    }


    public function findByPinPass($id, $pin)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p')
            ->from('GestionBundle:Patient', 'p')
            ->where("p.identifiant = :id")
            ->andWhere('p.pin = :pin')
            ->andWhere($qb->expr()->exists('
                SELECT p2  FROM ParametreBundle:Pass p2
                WHERE p2.identifiant = p.identifiant
                AND p2.pin = p.pin
                AND p2.actif = 1
            '))
            ->setParameter('id', $id)
            ->setParameter('pin', $pin);

        return $qb->getQuery()->getResult();
    }


    public function findByIdentifiant($id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p')
            ->from('GestionBundle:Patient', 'p')
           
            ->where("p.identifiant = :id")
            ->setParameter('id', $id);

        return $qb->getQuery()->getResult();
    }


    public function findIdByPersonne($id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p.id')
            ->from('GestionBundle:Patient', 'p')
            ->where("p.personne = :id")
            ->setParameter('id', $id);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findByParam($id, $pin)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p')
            ->from('GestionBundle:Patient', 'p')
            ->where("p.identifiant = :id")
            ->andWhere('p.pin = :pin')
            ->setParameter('id', $id)
            ->setParameter('pin', $pin);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findPatient($id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p', 'p2')
            ->from('GestionBundle:Patient', 'p')
            ->leftJoin("p.personne", "p2")
            ->where('p2.id = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();

    }


     public function lastConstantes($patient, $group = false)
    {
        $em  = $this->getEntityManager()->getConnection();

        $sql = <<<SQL
    SELECT DISTINCT c.libelle, p.valeur, c.unite 
FROM patient_constante p
JOIN (
SELECT MAX(`date`) AS date_mesure, constante_id
FROM patient_constante
WHERE patient_id = :patient_id1
GROUP BY  constante_id
) AS p_max ON (p.`date` = p_max.date_mesure AND p.constante_id = p_max.constante_id)
JOIN constante c ON c.id = p_max.constante_id
WHERE patient_id = :patient_id2
SQL;
        $query = $em->prepare($sql);

        $query->bindValue('patient_id1', $patient->getId());
        $query->bindValue('patient_id2', $patient->getId());

        $query->execute();

        //dump($query->fetchAll());exit;

        return $query->fetchAll();
        

      
    }



    public function photos($params = []) 
    {
        $qb = $this->createQueryBuilder('a')
      
            ->join('a.personne', 'p');
        if (!empty($params['matricule']) || !empty($params['nom'])) {
            $qb->leftJoin('p.photo', 'f');
        } else {
            $qb->join('p.photo', 'f');
        }
           
        
            if (!empty($params['corporate'])) {
                $qb->andWhere('p.corporate = :corporate');
                $qb->setParameter('corporate', $params['corporate']);
            }

            if (!empty($params['matricule'])) {
                $qb->andWhere('a.matricule = :matricule');
                $qb->setParameter('matricule', $params['matricule']);
            }


            if (!empty($params['nom'])) {
                $qb->andWhere("CONCAT(p.nom, ' ', p.prenom) LIKE LOWER(:nom)");
                $qb->setParameter('nom', "%".$params['nom']."%");
            }

        return $qb->orderBy('p.dateInscription', 'DESC')->getQuery();
            
    }



    public function findIdByUser($userId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('a.id')
            ->from('GestionBundle:Patient','a')
            ->innerJoin('a.personne', 'b')
            ->innerJoin('b.utilisateur', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $userId);

        try {
            $id = $qb->getQuery()->getSingleScalarResult();
        } catch (NoResultException $e) {
            $id = 0;
        }
        return $id;
    }

    public function findSearch($nom)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb
            ->select('a', 'b')
            ->from('GestionBundle:Patient','a')
            ->innerJoin('a.personne', 'b')
            ->where("b.nom LIKE :nom")
//  
            ->setParameter('nom', '%' . $nom . '%');

        return $qb->getQuery()->getResult();

    }


    public function findByNomPrenom($search, $corporate = null)
    {
        $parts = array_map('trim', explode(' ', $search, 2));

        if (count($parts) > 1) {
            $nom = $parts[0];
            $prenom = $parts[1];
        } else {
            $nom = $parts[0];
            $prenom = $parts[0];
        }

       

        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb
            ->select('a')
            ->from('GestionBundle:Patient','a')
            ->innerJoin('a.personne', 'b');

        if ($corporate) {
            $qb->andWhere('b.corporate = :corporate');
            $qb->setParameter('corporate', $corporate);
        }

        $qb->andWhere("b.nom = :nom AND b.prenom = :prenom")
//  
            ->setParameter('nom',  $nom )
            ->setParameter('prenom', $prenom);

        return $qb->getQuery()->getResult();

    }


    public function findByLikeNomPrenom($search, $corporate = null)
    {
        $parts = array_map('trim', explode(' ', $search, 2));

        if (count($parts) > 1) {
            $nom = $parts[0];
            $prenom = $parts[1];
        } else {
            $nom = $parts[0];
            $prenom = $parts[0];
        }

       

        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb
            ->select('a')
            ->from('GestionBundle:Patient','a')
            ->innerJoin('a.personne', 'b');

        if ($corporate) {
            $qb->andWhere('b.corporate = :corporate');
            $qb->setParameter('corporate', $corporate);
        }

        $qb->andWhere("b.nom = :nom AND b.prenom LIKE :prenom")
//  
            ->setParameter('nom',  $nom )
            ->setParameter('prenom', "%{$prenom}");

        return $qb->getQuery()->getResult();

    }


    public function findById($id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p', 'p2', 'u')
            ->from('GestionBundle:Patient', 'p')
            ->leftJoin("p.personne", "p2")
            ->join('')
            ->where('p2.id = :id')
            ->setParameter('id', $id);
    }


    //public function getCurrent


    public function statistiqueSexe($corporate = null, $pays = null, $hopital = null, $annee = null)
    {
        $qb = $this->createQueryBuilder('a');

        $qb->select("COUNT(a.id) as nbre, IFELSE(IFNULL(a.sexe, '') <> '', a.sexe, 'NP') AS sexe");
        $qb->join('a.personne', 'p2');
        $qb->join('GestionBundle:Patient ','p', 'WITH', 'p.personne = p2.id');
        $qb->leftJoin('p2.utilisateur', 'u');
       


        
        if ($corporate) {
            $qb->andWhere('p2.corporate = :corporate');
            $qb->setParameter('corporate', $corporate);
        }

        if ($pays) {
          
            $qb->andWhere('p.pays = :pays');
            $qb->setParameter('pays', $pays);
        }


        if ($hopital) {
            $qb->join('UtilisateurBundle:PersonneHopital', 'h', 'WITH', 'h.personne = p.id');
            $qb->andWhere('h.hopital = :hopital');
            $qb->setParameter('hopital', $hopital);
        }

        if ($annee) {
            $qb->andWhere('YEAR(p2.dateInscription) = :annee');
            $qb->setParameter('annee', $annee);
        }

        $qb->groupby("sexe");

        return $qb->getQuery()->getResult();

    }



}
