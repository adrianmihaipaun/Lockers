<?php

namespace App\Repository;

use App\Entity\LockersBoxes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LockersBoxes|null find($id, $lockMode = null, $lockVersion = null)
 * @method LockersBoxes|null findOneBy(array $criteria, array $orderBy = null)
 * @method LockersBoxes[]    findAll()
 * @method LockersBoxes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LockersBoxesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LockersBoxes::class);
    }

    // /**
    //  * @return LockersBoxes[] Returns an array of LockersBoxes objects
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
    public function findOneBySomeField($value): ?LockersBoxes
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
