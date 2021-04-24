<?php

namespace App\Repository;

use App\Entity\LockersBoxesTypes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LockersBoxesTypes|null find($id, $lockMode = null, $lockVersion = null)
 * @method LockersBoxesTypes|null findOneBy(array $criteria, array $orderBy = null)
 * @method LockersBoxesTypes[]    findAll()
 * @method LockersBoxesTypes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LockersBoxesTypesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LockersBoxesTypes::class);
    }

    // /**
    //  * @return LockersBoxesTypes[] Returns an array of LockersBoxesTypes objects
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
    public function findOneBySomeField($value): ?LockersBoxesTypes
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
