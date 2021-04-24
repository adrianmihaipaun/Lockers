<?php

namespace App\Repository;

use App\Entity\LockersSchedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LockersSchedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method LockersSchedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method LockersSchedule[]    findAll()
 * @method LockersSchedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LockersScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LockersSchedule::class);
    }

    // /**
    //  * @return LockersSchedule[] Returns an array of LockersSchedule objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LockersSchedule
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
