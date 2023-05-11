<?php

namespace App\Repository;

use App\Entity\SystemStatus;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<SystemStatus>
 *
 * @method SystemStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method SystemStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method SystemStatus[]    findAll()
 * @method SystemStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SystemStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SystemStatus::class);
    }
    //Method created for User subscribe  
    public function findSubscribedByUser(User $user)
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.subscriptions', 'sub')
            ->andWhere('sub.user = :user')
            ->andWhere('sub.isSubscribed = true')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function save(SystemStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SystemStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SystemStatus[] Returns an array of SystemStatus objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SystemStatus
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findLatestSystemStatus()
{
    $qb = $this->createQueryBuilder('s');
    $qb->select('DISTINCT s.systemName, s.status, s.updatedAt');
    $qb->where($qb->expr()->eq('s.createdAt', '(SELECT MAX(s2.createdAt) FROM App\Entity\SystemStatus s2 WHERE s.systemName = s2.systemName)'));
    $qb->orderBy('s.systemName');

    return $qb->getQuery()->getResult();
}
}
