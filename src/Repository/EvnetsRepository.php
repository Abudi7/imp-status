<?php

namespace App\Repository;

use App\Entity\Evnets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evnets>
 *
 * @method Evnets|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evnets|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evnets[]    findAll()
 * @method Evnets[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvnetsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evnets::class);
    }

//    /**
//     * @return Evnets[] Returns an array of Evnets objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Evnets
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
