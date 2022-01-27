<?php

namespace App\Repository;

use App\Entity\LaboralSector;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LaboralSector|null find($id, $lockMode = null, $lockVersion = null)
 * @method LaboralSector|null findOneBy(array $criteria, array $orderBy = null)
 * @method LaboralSector[]    findAll()
 * @method LaboralSector[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LaboralSectorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LaboralSector::class);
    }

    // /**
    //  * @return LaboralSector[] Returns an array of LaboralSector objects
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
    public function findOneBySomeField($value): ?LaboralSector
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
