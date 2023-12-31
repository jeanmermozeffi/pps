<?php

namespace PS\ParametreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * PassRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VilleRepository extends EntityRepository
{
    /**
     * On teste si le couple PIN + ID existe
     * @param  int $pin
     * @param  int $identifiant
     * @return int
     */
    public function exists($ville, $where = [])
    {
        $em  = $this->getEntityManager();
        $sql = 'SELECT a.id FROM ParametreBundle:Ville a WHERE (a.id = ?1 OR a.nom = ?2)';
        $i   = $j   = 3;
        foreach ($where as $field => $value) {
            $sql .= ' AND ' . $field . ' = ?' . $i;
            $i++;
        }
        $qb = $em
            ->createQuery($sql)
            ->setParameter(1, $ville)
            ->setParameter(2, $ville);

        foreach ($where as $value) {
            $qb->setParameter($j, $value);
            $j++;
        }
        try {
            $result = $qb->getOneOrNullResult();
        } catch (NoResultException $e) {
            $result = null;
        }

        return $result;
    }

}
