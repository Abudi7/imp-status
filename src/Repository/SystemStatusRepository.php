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

    public function findSubscribedUsersForSystem(string $system): array
{
    return $this->createQueryBuilder('ss')
        ->select('u')
        ->join('ss.user', 'u')
        ->join('ss.subscriptions', 'sub')
        ->where('ss.system = :system')
        ->andWhere('sub.isSubscribed = true')
        ->setParameter('system', $system)
        ->getQuery()
        ->getResult();
}

public function findFutureMaintenance(): array
{
    $qb = $this->createQueryBuilder('sys');
    $now = new \DateTime();

    // Filter maintenance events by the presence of maintenance start and end dates,
    // and where the maintenance start date is greater than the current time
    $qb->where($qb->expr()->isNotNull('sys.maintenanceStart'))
       ->andWhere($qb->expr()->isNotNull('sys.maintenanceEnd'))
       ->andWhere($qb->expr()->gt('sys.maintenanceStart', ':now'))
       ->setParameter('now', $now)
       ->orderBy('sys.maintenanceStart', 'ASC');

    return $qb->getQuery()->getResult();
}

/**
     * Find system statuses by status name.
     *
     * @param string $status The status name to filter by.
     * @return SystemStatus[] An array of system statuses.
     */
    public function findByStatus(string $status): array
    {
        return $this->createQueryBuilder('ss')
            ->join('ss.status', 's')
            ->where('s.name = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getResult();
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
