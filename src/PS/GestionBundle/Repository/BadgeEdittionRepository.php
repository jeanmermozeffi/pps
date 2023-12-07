<?php

namespace App\Repository;

use App\Entity\BadgeEdittion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BadgeEdittion>
 *
 * @method BadgeEdittion|null find($id, $lockMode = null, $lockVersion = null)
 * @method BadgeEdittion|null findOneBy(array $criteria, array $orderBy = null)
 * @method BadgeEdittion[]    findAll()
 * @method BadgeEdittion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BadgeEdittionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BadgeEdittion::class);
    }

    public function add(BadgeEdittion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BadgeEdittion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return BadgeEdittion[] Returns an array of BadgeEdittion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BadgeEdittion
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
