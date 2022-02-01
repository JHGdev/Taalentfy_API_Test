<?php

namespace App\Repository;

use App\Entity\OfferCriteriaTestB;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OfferCriteriaTestB|null find($id, $lockMode = null, $lockVersion = null)
 * @method OfferCriteriaTestB|null findOneBy(array $criteria, array $orderBy = null)
 * @method OfferCriteriaTestB[]    findAll()
 * @method OfferCriteriaTestB[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferCriteriaTestBRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OfferCriteriaTestB::class);
    }

    // /**
    //  * @return OfferCriteriaTestB[] Returns an array of OfferCriteriaTestB objects
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
    public function findOneBySomeField($value): ?OfferCriteriaTestB
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
