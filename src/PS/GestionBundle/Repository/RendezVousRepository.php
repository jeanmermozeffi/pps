<?php

namespace PS\GestionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RendezVousRepository extends EntityRepository
{
    

    /**
     * Teste si la date choisie est libre pour un medecin
     *
     * @param datetime $datetime
     * @param int $medecin
     * @param object $rendezVous
     * @return void
     */
    public function checkDateAvailability($datetime, $medecin, $rendezVous)
    {
        $qb   = $this->getEntityManager()->createQueryBuilder();
        $stmt = $qb->select('COUNT(r.id)')
            ->from('GestionBundle:RendezVous', 'r')
           
            ->andWhere('r.statutRendezVous = 0')
            ->andWhere('r.dateRendezVous = :datetime')
            ->andWhere('r.medecin = :medecin');

        if ($rendezVous->getId()) {
            $stmt->andWhere('r.id <> :id');
            $stmt->setParameter('id', $rendezVous->getId());
        }
        
        $stmt->setParameter('datetime', $datetime->format('Y-m-d H:i'))
            ->setParameter('medecin', $medecin->getId());

       


        $count = $stmt->getQuery()->getSingleScalarResult();

        return $count == 0;
    
    }



    public function countTodayPendingByMedecin($medecin)
    {
        $id = is_object($medecin) ? $medecin->getId() : $medecin;
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('COUNT(r.id)')
            ->from('GestionBundle:RendezVous', 'r')
            ->where("r.medecin = :id")
            ->andWhere('r.statutRendezVous = 0')
            ->andWhere('DATE(r.dateRendezVous) = CURRENT_DATE()')
            ->setParameter('id', $id);
        return $qb->getQuery()->getSingleScalarResult();
    }


    /**
     * @param $id
     * @param $startDate
     * @param null $endDate
     * @return mixed
     */
    public function findPatientRendezVous($id, $startDate = null, $endDate = null, $limit = null, $offset = null)
    {
        $stmt   = $this->createQueryBuilder('r')
        
            ->andWhere("r.patient = :id");

        $startDate  = $this->convertDate($startDate);
        $endDate    = $this->convertDate($endDate);
        $parameters = ['id' => $id];

        if ($startDate) {
            if (!$endDate || ($startDate == $endDate)) {
                $stmt->andWhere('DATE(r.dateRendezVous) = :startDate');
                $parameters['startDate'] = $startDate;
            } else {

                $stmt->andWhere('DATE(r.dateRendezVous) >= :startDate');
                $stmt->andWhere('DATE(r.dateRendezVous) <= :endDate');
                $parameters['startDate'] = $startDate;
                $parameters['endDate']   = $endDate;
            }
        }

        $stmt
            ->orderBy("FIELD(r.statutRendezVous, '-1', '1', '0')", 'DESC')
            ->addOrderBy('r.dateRendezVous', 'DESC')
            ->setParameters($parameters)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return $stmt->getQuery()->getResult();
    }

    /**
     * @param $date
     */
    private function convertDate($date)
    {
        if ($date) {
            return (new \DateTime($date))->format('Y-m-d');
        }
    }


    /**
     * @return mixed
     */
    public function getFromToday()
    {
        $stmt = $this->getEntityManager()->getConnection();
        
        $sql1 = <<<SQL
SELECT
r.patient_id AS patient_id
, p1.identifiant
, p1.pin
, p.contact
, DATE_FORMAT(date_rendez_vous, '%H:%i') AS date_rappel
, u.email
, CONCAT_WS(" ", p.nom, p.prenom) AS title
, 2 AS type
FROM rendez_vous r
    JOIN medecin m ON r.medecin_id = m.id
    JOIN personne p ON p.id = m.personne_id
    
    JOIN patient p1 ON r.patient_id = p1.id
    JOIN personne p2 ON p2.id = p1.personne_id
    JOIN utilisateur u ON u.personne_id = p2.id
    WHERE 
    ((CURRENT_TIMESTAMP <= (date_rendez_vous - INTERVAL 1 HOUR))
        AND 
    (DATE(date_rendez_vous) = DATE(CURRENT_TIMESTAMP))
    AND statut_rendez_vous = 0)
    AND (NOT EXISTS (SELECT patient_id FROM envoi_rappel WHERE date_envoi = CURRENT_DATE AND type_rappel = 2))
SQL;
        $sql2 = <<<SQL
SELECT
p.id AS patient_id
, p.identifiant
, p.pin
, p1.contact
, '' AS date_rappel
, u.email
, v.vaccin AS title
, 1 AS type
FROM patient_vaccin v
    JOIN patient p ON v.patient_id = p.id
    JOIN personne p1 ON p1.id = p.personne_id
    JOIN utilisateur u ON u.personne_id = p1.id
    WHERE DATE(rappel) = CURRENT_DATE
    AND NOT EXISTS (SELECT patient_id FROM envoi_rappel WHERE date_envoi = CURRENT_DATE AND type_rappel = 1)
    AND rappel IS NOT NULL
SQL;

        $sql = "($sql1) UNION ALL ($sql2)";

        $query = $stmt->query($sql);
        return $query->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * @param $id
     * @param $startDate
     * @param null $endDate
     * @return mixed
     */
    public function findByMedecin($id, $startDate = null, $endDate = null, $ajax = true,  $limit = null, $offset = null)
    {
        if ($ajax) {
            $fields = [
                'r.id'
                , 'r.dateRendezVous as start'
                , 'r.dateAnnulationRendezVous'
                , 'r.motifAnnulationRendezVous'
                , 'r.statutRendezVous'
                , 'r.libRendezVous AS lib'
                , 'CONCAT_WS(\' \', p2.nom, p2.prenom) AS title'
            ];
        } else {
            $fields = [
                'r.id'
                , 'r.dateRendezVous'
                , 'r.dateAnnulationRendezVous'
                , 'r.statutRendezVous'
                , 'r.libRendezVous'
                , 'CONCAT_WS(\' \', p2.nom, p2.prenom) AS patient'
            ];
        }
        $qb   = $this->getEntityManager()->createQueryBuilder();
        $stmt = $qb->select(implode(',', $fields))
            ->from('GestionBundle:RendezVous', 'r')
            ->join('r.patient', 'p')
            ->join('UtilisateurBundle:Personne', 'p2', 'WITH', 'p2.id = p.personne')
            ->where("r.medecin = :id");

        $startDate  = $this->convertDate($startDate);
        $endDate    = $this->convertDate($endDate);
        $parameters = ['id' => $id];

        if ($startDate) {
            if (!$endDate || ($startDate == $endDate)) {
                $stmt->andWhere('DATE(r.dateRendezVous) = :startDate');
                $parameters['startDate'] = $startDate;
            } else {

                $stmt->andWhere('DATE(r.dateRendezVous) >= :startDate');
                $stmt->andWhere('DATE(r.dateRendezVous) <= :endDate');
                $parameters['startDate'] = $startDate;
                $parameters['endDate']   = $endDate;
            }
        }

        $stmt->orderBy('r.dateRendezVous', 'DESC')
            ->setParameters($parameters)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return $stmt->getQuery()->getResult();
    }



    public function findAllPatientRendezVous($id, $startDate = null, $endDate = null, $limit = null, $offset = null, $from = "patient", $search = [])
    {
        $parameters = ['id' => $id];
        $qb   = $this->getEntityManager()->createQueryBuilder('r');
        $stmt = $qb->select('r')->from('GestionBundle:RendezVous', 'r')
                    ->join('r.medecin', 'm')
                    ->join('UtilisateurBundle:Personne', 'p2', 'WITH', 'p2.id = m.personne');

        if ($from == 'patient') {
            $stmt->where("r.patient = :id");
        } else {
            $stmt->where('r.medecin = :id');
        }

        if (isset($search['hopital'])) {
            $parameters['hopital'] = $search['hopital'];
            $stmt->andWhere('m.hopital = :hopital');
        }

        if (isset($search['patient'])) {
            $parameters['patient'] = $search['patient'];
            $stmt->andWhere('r.patient = :patient');
        }
                    

        $startDate  = $this->convertDate($startDate);
        $endDate    = $this->convertDate($endDate);
        

        if ($startDate) {
            if (!$endDate || ($startDate == $endDate)) {
                $stmt->andWhere('DATE(r.dateRendezVous) = :startDate');
                $parameters['startDate'] = $startDate;
            } else {

                $stmt->andWhere('DATE(r.dateRendezVous) >= :startDate');
                $stmt->andWhere('DATE(r.dateRendezVous) <= :endDate');
                $parameters['startDate'] = $startDate;
                $parameters['endDate']   = $endDate;
            }
        }

        $stmt->orderBy('r.dateRendezVous', 'DESC')
            ->setParameters($parameters)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return $stmt->getQuery()->getResult();
    }



    public function findAllMedecinRendezVous($id, $startDate = null, $endDate = null, $limit = null, $offset = null) 
    {
        return $this->findAllPatientRendezVous($id, $startDate, $endDate, $limit, $offset, 'medecin');
    }



    /**
     * @param $id
     * @param $startDate
     * @param null $endDate
     * @return mixed
     */
    public function findAllRendezVous($id, $startDate = null, $endDate = null, $limit = null, $offset = null, $from = "patient")
    {
        $qb   = $this->getEntityManager()->createQueryBuilder('r');
        $stmt = $qb->select('r')
                    ->from('GestionBundle:RendezVous', 'r')
                    ->join('r.medecin', 'm')
                    ->join('UtilisateurBundle:Personne', 'p2', 'WITH', 'p2.id = m.personne');

        if ($from == 'patient') {
            $stmt->where("r.patient = :id");
        } else {
            $stmt->where('r.medecin = :id');
        }
                    

        $startDate  = $this->convertDate($startDate);
        $endDate    = $this->convertDate($endDate);
        $parameters = ['id' => $id];

        if ($startDate) {
            if (!$endDate || ($startDate == $endDate)) {
                $stmt->andWhere('DATE(r.dateRendezVous) = :startDate');
                $parameters['startDate'] = $startDate;
            } else {

                $stmt->andWhere('DATE(r.dateRendezVous) >= :startDate');
                $stmt->andWhere('DATE(r.dateRendezVous) <= :endDate');
                $parameters['startDate'] = $startDate;
                $parameters['endDate']   = $endDate;
            }
        }

        $stmt->orderBy('r.dateRendezVous', 'DESC')
           
            ->setParameters($parameters)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return $stmt->getQuery()->getResult();
    }

    /**
     * @param $id
     * @param null $startDate
     * @param null $endDate
     */
    public function findByPatient($id = null, $startDate = null, $endDate = null)
    {
        $stmt = $this->getEntityManager()->getConnection();
        /*$stmt = $qb->select('r.id, r.dateRendezVous as start,  r.dateAnnulationRendezVous, r.statutRendezVous, r.libRendezVous as lib, CONCAT_WS(\' \', p2.nom, p2.prenom) AS title')
        ->from('GestionBundle:RendezVous', 'r')
        ->join('r.medecin', 'm')
        ->join('UtilisateurBundle:Personne', 'p2', 'WITH', 'p2.id = m.id')
        ->where("r.patient = :id");*/

        $sql1 = <<<SQL
SELECT r.id
, r.date_rendez_vous as start
, r.date_annulation_rendez_vous
, r.statut_rendez_vous
, CASE statut_rendez_vous
    WHEN 1 THEN '#50af01'
    WHEN -1 THEN '#ff0000'
    WHEN 0 THEN '#016daf'
END as color
, '#fff' as textColor
, r.lib_rendez_vous as lib
, r.motif_annulation_rendez_vous

, CONCAT_WS(" ", p.nom, p.prenom) AS title
FROM rendez_vous r
    JOIN medecin m ON r.medecin_id = m.id
    JOIN personne p ON p.id = m.personne_id
SQL;
        $sql2 = <<<SQL
SELECT v.id
, CONVERT(rappel, DATETIME) AS start
, '0000-00-00 00:00:00' AS date_annulation_rendez_vous
, 2 AS statut_rendez_vous
, '#f1c232' as color
, '#fff' as textColor
, 'Rappel' AS lib
, '' AS motif_annulation_rendez_vous
, vaccin AS title
FROM patient_vaccin v
    JOIN patient p ON v.patient_id = p.id
SQL;
        $where1      = [];
        $where2      = [];
        $parameters1 = [];
        $parameters2 = [];

        if ($id) {
            $id                     = intval($id);
            $parameters1['id_1']    = $id;
            $parameters2['id_2']    = $id;
            $where1['r.patient_id'] = ':id_1';
            $where2['v.patient_id'] = ':id_2';
        }

        $startDate = $this->convertDate($startDate);
        $endDate   = $this->convertDate($endDate);

        if ($startDate) {
            if (!$endDate || ($startDate == $endDate)) {
                $where1['date_rendez_vous'] = ':startDate';
                $where2['rappel']           = ':rappel';
                $parameters1['startDate']   = $parameters2['rappel']   = $startDate;
            } else {

                $where1['date_rendez_vous']   = '>= :startDate';
                $where1['__date_rendez_vous'] = '<= :endDate';
                $where2['rappel']             = '>= :startRappel';
                $where2['__rappel']           = '<= :endRappel';
                $parameters1['startDate']     = $parameters2['startRappel']     = $startDate;
                $parameters1['endDate']       = $parameters2['endRappel']       = $endDate;
            }
        }

        $where1_str = $where2_str = [];

        foreach ($where1 as $field => $val) {
            $field    = str_replace('`', '``', $field);
            $operator = '=';
            if (strpos($val, '=') !== false) {
                list($operator, $val) = explode(' ', $val);
                $val                  = trim($val);
            }

            if (strpos($field, 'date_') === 0 || $field === 'rappel') {
                $field = "DATE({$field})";
            }
            $where1_str[] = str_replace('__', '', "{$field} {$operator} {$val}");
        }

        foreach ($where2 as $field => $val) {
            $field    = str_replace('`', '``', $field);
            $operator = '=';
            if (strpos($val, '=') !== false) {
                list($operator, $val) = explode(' ', $val);
                $val                  = trim($val);
            }

            if (strpos($field, 'date_') === 0 || $field === 'rappel') {
                $field = "DATE({$field})";
            }
            $where2_str[] = str_replace('__', '', "{$field} {$operator} {$val}");
        }

        if ($where1_str) {
            $sql1 .= " WHERE {$where1_str[0]}";
            $sql2 .= " WHERE {$where2_str[0]}";
            unset($where1_str[0], $where2_str[0]);
            if ($where1_str) {
                $sql1 .= " AND " . implode(' AND ', $where1_str);
                $sql2 .= " AND " . implode(' AND ', $where2_str);
                $sql2 .= " AND rappel IS NOT NULL";
            }

        }

        $sql = "($sql1) UNION ALL  ($sql2)";
        $sql .= ' ORDER BY start';

        $query = $stmt->prepare($sql);
        $query->execute(array_merge($parameters1, $parameters2));
        return $query->fetchAll();
    }


    public function countByPatient($id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('COUNT(r.id)')
            ->from('GestionBundle:RendezVous', 'r')
            ->where("r.patient = :id")
            ->setParameter('id', $id);
        return $qb->getQuery()->getSingleScalarResult();
    }


    public function countByMedecin($id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('COUNT(r.id)')
            ->from('GestionBundle:RendezVous', 'r')
            ->where("r.medecin = :id")
            ->setParameter('id', $id);
        return $qb->getQuery()->getSingleScalarResult();
    }
}
