<?php

namespace App\Repository;

use App\Entity\Events;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Events>
 *
 * @method Events|null find($id, $lockMode = null, $lockVersion = null)
 * @method Events|null findOneBy(array $criteria, array $orderBy = null)
 * @method Events[]    findAll()
 * @method Events[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Events::class);
    }

//    /**
//     * @return Events[] Returns an array of Events objects
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

//    public function findOneBySomeField($value): ?Events
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function getSystemStatus($systemId)
    {
        $qb = $this->createQueryBuilder('e')
            ->select('e.type')
            ->andWhere('e.system = :systemId')
            ->andWhere('e.start <= :now')
            ->andWhere('e.end >= :now')
            ->orderBy('e.start', 'DESC')
            ->setMaxResults(1)
            ->setParameter('systemId', $systemId)
            ->setParameter('now', new \DateTime());

        $query = $qb->getQuery();
        $result = $query->getOneOrNullResult();

        return $result ? $result['type'] : 'Unknown';
    }

    /**
     * Find future maintenance events for a specific system.
     *
     * @param System $system The system entity
     * @param \DateTime $currentDateTime The current date and time
     * @return Events[]
     */
    public function findFutureMaintenanceEvents(System $system): array
    {
        $now = new \DateTime();
        
        return $this->createQueryBuilder('e')
            ->andWhere('e.system = :system')
            ->andWhere('e.type = :type')
            ->andWhere('e.start > :now')
            ->setParameter('system', $system)
            ->setParameter('type', 'maintenance')
            ->setParameter('now', $now)
            ->orderBy('e.start', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find all maintenance events, whether past, present, or future.
     *
     * @return Events[]
     */
    public function  findFutureMaintenanceEvent(): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.type = :type')
            ->setParameter('type', 'maintenance')
            ->orderBy('e.start', 'ASC')
            ->getQuery()
            ->getResult();

    }



    /**
     * Find future and ongoing maintenance events.
     *
     * @return Events[]
     */
    public function findFutureAndOngoingMaintenanceEvents(): array
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('e')
        ->andWhere('e.type = :type')
        ->andWhere('(e.start > :now OR (e.start <= :now AND e.end >= :now))')
        ->setParameter('type', 'maintenance')
        ->setParameter('now', $now)
        ->orderBy('e.start', 'ASC')
        ->getQuery()
        ->getResult();
    }


}
