<?php

namespace App\Repository;

use App\Entity\Lockers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lockers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lockers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lockers[]    findAll()
 * @method Lockers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LockersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lockers::class);
    }

    // /**
    //  * @return Lockers[] Returns an array of Lockers objects
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
    public function findOneBySomeField($value): ?Lockers
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
