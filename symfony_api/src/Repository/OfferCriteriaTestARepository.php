<?php

namespace App\Repository;

use App\Entity\OfferCriteriaTestA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OfferCriteriaTestA|null find($id, $lockMode = null, $lockVersion = null)
 * @method OfferCriteriaTestA|null findOneBy(array $criteria, array $orderBy = null)
 * @method OfferCriteriaTestA[]    findAll()
 * @method OfferCriteriaTestA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferCriteriaTestARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OfferCriteriaTestA::class);
    }

    // /**
    //  * @return OfferCriteriaTestA[] Returns an array of OfferCriteriaTestA objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OfferCriteriaTestA
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
